<?php

	namespace Uneak\AssetsBundle\Block;

	use Uneak\AssetsBundle\Block\Exception\TransformationFailedException;
	use Uneak\AssetsBundle\Block\Exception\OutOfBoundsException;
	use Symfony\Component\PropertyAccess\PropertyPath;
	use Uneak\AssetsBundle\Block\Exception\LogicException;
	use Uneak\AssetsBundle\Block\Exception\RuntimeException;
	use Uneak\AssetsBundle\Block\Exception\UnexpectedTypeException;
	use Uneak\AssetsBundle\Block\Util\BlockUtil;
	use Uneak\AssetsBundle\Block\Util\InheritDataAwareIterator;
	use Uneak\AssetsBundle\Block\Util\OrderedHashMap;

	/**
	 * Block represents a block.
	 *
	 * To implement your own block fields, you need to have a thorough understanding
	 * of the data flow within a block. A block stores its data in three different
	 * representations:
	 *
	 *   (1) the "model" format required by the block's object
	 *   (2) the "normalized" format for internal processing
	 *   (3) the "view" format used for display
	 *
	 * A date field, for example, may store a date as "Y-m-d" string (1) in the
	 * object. To facilitate processing in the field, this value is normalized
	 * to a DateTime object (2). In the HTML representation of your block, a
	 * localized string (3) is presented to and modified by the user.
	 *
	 * In most cases, format (1) and format (2) will be the same. For example,
	 * a checkbox field uses a Boolean value for both internal processing and
	 * storage in the object. In these cases you simply need to set a value
	 * transformer to convert between formats (2) and (3). You can do this by
	 * calling addViewTransformer().
	 *
	 * In some cases though it makes sense to make format (1) configurable. To
	 * demonstrate this, let's extend our above date field to store the value
	 * either as "Y-m-d" string or as timestamp. Internally we still want to
	 * use a DateTime object for processing. To convert the data from string/integer
	 * to DateTime you can set a normalization transformer by calling
	 * addNormTransformer(). The normalized data is then converted to the displayed
	 * data as described before.
	 *
	 * The conversions (1) -> (2) -> (3) use the transform methods of the transformers.
	 * The conversions (3) -> (2) -> (1) use the reverseTransform methods of the transformers.
	 *
	 * @author Fabien Potencier <fabien@symfony.com>
	 * @author Bernhard Schussek <bschussek@gmail.com>
	 */
	class Block implements \IteratorAggregate, BlockInterface {
		/**
		 * The block's configuration.
		 *
		 * @var BlockConfigInterface
		 */
		private $config;

		/**
		 * The parent of this block.
		 *
		 * @var BlockInterface
		 */
		private $parent;

		/**
		 * The children of this block.
		 *
		 * @var BlockInterface[] A map of BlockInterface instances
		 */
		private $children;

		/**
		 * The block data in model format.
		 *
		 * @var mixed
		 */
		private $modelData;

		/**
		 * The block data in normalized format.
		 *
		 * @var mixed
		 */
		private $normData;

		/**
		 * The block data in view format.
		 *
		 * @var mixed
		 */
		private $viewData;


		/**
		 * Whether the block's data has been initialized.
		 *
		 * When the data is initialized with its default value, that default value
		 * is passed through the transformer chain in order to synchronize the
		 * model, normalized and view format for the first time. This is done
		 * lazily in order to save performance when {@link setData()} is called
		 * manually, making the initialization with the configured default value
		 * superfluous.
		 *
		 * @var bool
		 */
		private $defaultDataSet = false;

		/**
		 * Whether setData() is currently being called.
		 *
		 * @var bool
		 */
		private $lockSetData = false;

		/**
		 * Creates a new block based on the given configuration.
		 *
		 * @param BlockConfigInterface $config The block configuration
		 *
		 */
		public function __construct(BlockConfigInterface $config) {

			// If the block inherits the data from its parent, it is not necessary
			// to call setData() with the default data.
			if ($config->getInheritData()) {
				$this->defaultDataSet = true;
			}

			$this->config = $config;
			$this->children = new OrderedHashMap();
		}

		public function __clone() {
			$this->children = clone $this->children;

			foreach ($this->children as $key => $child) {
				$this->children[$key] = clone $child;
			}
		}

		/**
		 * {@inheritdoc}
		 */
		public function getConfig() {
			return $this->config;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getName() {
			return $this->config->getName();
		}

		/**
		 * {@inheritdoc}
		 */
		public function getPropertyPath() {
			if (null !== $this->config->getPropertyPath()) {
				return $this->config->getPropertyPath();
			}

			if (null === $this->getName() || '' === $this->getName()) {
				return;
			}

			$parent = $this->parent;

			while ($parent && $parent->getConfig()->getInheritData()) {
				$parent = $parent->getParent();
			}

			if ($parent && null === $parent->getConfig()->getDataClass()) {
				return new PropertyPath('[' . $this->getName() . ']');
			}

			return new PropertyPath($this->getName());
		}

		/**
		 * {@inheritdoc}
		 */
		public function setParent(BlockInterface $parent = null) {

			if (null !== $parent && '' === $this->config->getName()) {
				throw new LogicException('A block with an empty name cannot have a parent block.');
			}

			$this->parent = $parent;

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getParent() {
			return $this->parent;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getRoot() {
			return $this->parent ? $this->parent->getRoot() : $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function isRoot() {
			return null === $this->parent;
		}

		/**
		 * {@inheritdoc}
		 */
		public function setData($modelData) {
			// If the block inherits its parent's data, disallow data setting to
			// prevent merge conflicts
			if ($this->config->getInheritData()) {
				throw new RuntimeException('You cannot change the data of a block inheriting its parent data.');
			}

			// Don't allow modifications of the configured data if the data is locked
			if ($this->config->getDataLocked() && $modelData !== $this->config->getData()) {
				return $this;
			}

			if (is_object($modelData)) {
				$modelData = clone $modelData;
			}

			if ($this->lockSetData) {
				throw new RuntimeException('A cycle was detected. Listeners to the PRE_SET_DATA event must not call setData(). You should call setData() on the BlockEvent object instead.');
			}

			$this->lockSetData = true;
			$dispatcher = $this->config->getEventDispatcher();

			// Hook to change content of the data
			if ($dispatcher->hasListeners(BlockEvents::PRE_SET_DATA)) {
				$event = new BlockEvent($this, $modelData);
				$dispatcher->dispatch(BlockEvents::PRE_SET_DATA, $event);
				$modelData = $event->getData();
			}

			// Treat data as strings unless a value transformer exists
			if (!$this->config->getViewTransformers() && !$this->config->getModelTransformers() && is_scalar($modelData)) {
				$modelData = (string)$modelData;
			}

			// Synchronize representations - must not change the content!
			$normData = $this->modelToNorm($modelData);
			$viewData = $this->normToView($normData);

			// Validate if view data matches data class (unless empty)
			if (!BlockUtil::isEmpty($viewData)) {
				$dataClass = $this->config->getDataClass();

				if (null !== $dataClass && !$viewData instanceof $dataClass) {
					$actualType = is_object($viewData)
						? 'an instance of class ' . get_class($viewData)
						: 'a(n) ' . gettype($viewData);

					throw new LogicException(
						'The block\'s view data is expected to be an instance of class ' .
						$dataClass . ', but is ' . $actualType . '. You can avoid this error ' .
						'by setting the "data_class" option to null or by adding a view ' .
						'transformer that transforms ' . $actualType . ' to an instance of ' .
						$dataClass . '.'
					);
				}
			}

			$this->modelData = $modelData;
			$this->viewData = $viewData;
			$this->defaultDataSet = true;
			$this->lockSetData = false;

			// It is not necessary to invoke this method if the block doesn't have children,
			if (count($this->children) > 0) {
				// Update child blocks from the data
				$iterator = new InheritDataAwareIterator($this->children);
				$iterator = new \RecursiveIteratorIterator($iterator);
				$this->config->getDataMapper()->mapDataToBlocks($viewData, $iterator);
			}

			if ($dispatcher->hasListeners(BlockEvents::POST_SET_DATA)) {
				$event = new BlockEvent($this, $modelData);
				$dispatcher->dispatch(BlockEvents::POST_SET_DATA, $event);
			}

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getData() {
			if ($this->config->getInheritData()) {
				if (!$this->parent) {
					throw new RuntimeException('The block is configured to inherit its parent\'s data, but does not have a parent.');
				}

				return $this->parent->getData();
			}

			if (!$this->defaultDataSet) {
				$this->setData($this->config->getData());
			}

			return $this->modelData;
		}


		/**
		 * {@inheritdoc}
		 */
		public function getNormData() {
			if ($this->config->getInheritData()) {
				if (!$this->parent) {
					throw new RuntimeException('The block is configured to inherit its parent\'s data, but does not have a parent.');
				}

				return $this->parent->getNormData();
			}

			if (!$this->defaultDataSet) {
				$this->setData($this->config->getData());
			}

			return $this->normData;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getViewData() {
			if ($this->config->getInheritData()) {
				if (!$this->parent) {
					throw new RuntimeException('The block is configured to inherit its parent\'s data, but does not have a parent.');
				}

				return $this->parent->getViewData();
			}

			if (!$this->defaultDataSet) {
				$this->setData($this->config->getData());
			}

			return $this->viewData;
		}

		/**
		 * {@inheritdoc}
		 */
		public function initialize() {
			if (null !== $this->parent) {
				throw new RuntimeException('Only root blocks should be initialized.');
			}

			// Guarantee that the *_SET_DATA events have been triggered once the
			// block is initialized. This makes sure that dynamically added or
			// removed fields are already visible after initialization.
			if (!$this->defaultDataSet) {
				$this->setData($this->config->getData());
			}

			return $this;
		}


		/**
		 * {@inheritdoc}
		 */
		public function isEmpty() {
			foreach ($this->children as $child) {
				if (!$child->isEmpty()) {
					return false;
				}
			}

			return BlockUtil::isEmpty($this->modelData) ||
			// arrays, countables
			0 === count($this->modelData) ||
			// traversables that are not countable
			($this->modelData instanceof \Traversable && 0 === iterator_count($this->modelData));
		}


		/**
		 * {@inheritdoc}
		 */
		public function all() {
			return iterator_to_array($this->children);
		}

		/**
		 * {@inheritdoc}
		 */
		public function add($child, $type = null, array $options = array()) {

			// Obtain the view data
			$viewData = null;

			// If setData() is currently being called, there is no need to call
			// mapDataToBlocks() here, as mapDataToBlocks() is called at the end
			// of setData() anyway. Not doing this check leads to an endless
			// recursion when initializing the block lazily and an event listener
			// (such as ResizeBlockListener) adds fields depending on the data:
			//
			//  * setData() is called, the block is not initialized yet
			//  * add() is called by the listener (setData() is not complete, so
			//    the block is still not initialized)
			//  * getViewData() is called
			//  * setData() is called since the block is not initialized yet
			//  * ... endless recursion ...
			//
			// Also skip data mapping if setData() has not been called yet.
			// setData() will be called upon block initialization and data mapping
			// will take place by then.
			if (!$this->lockSetData && $this->defaultDataSet && !$this->config->getInheritData()) {
				$viewData = $this->getViewData();
			}

			if (!$child instanceof BlockInterface) {
				if (!is_string($child) && !is_int($child)) {
					throw new UnexpectedTypeException($child, 'string, integer or Uneak\AssetsBundle\Block\BlockInterface');
				}

				if (null !== $type && !is_string($type) && !$type instanceof BlockTypeInterface) {
					throw new UnexpectedTypeException($type, 'string or Uneak\AssetsBundle\Block\BlockTypeInterface');
				}

				// Never initialize child blocks automatically
				$options['auto_initialize'] = false;

				if (null === $type && null === $this->config->getDataClass()) {
					$type = 'Uneak\AssetsBundle\Block\Extension\Core\Type\TextType';
				}

				if (null === $type) {
					$child = $this->config->getBlockFactory()->createForProperty($this->config->getDataClass(), $child, null, $options);
				} else {
					$child = $this->config->getBlockFactory()->createNamed($child, $type, null, $options);
				}
			} elseif ($child->getConfig()->getAutoInitialize()) {
				throw new RuntimeException(sprintf(
					'Automatic initialization is only supported on root blocks. You ' .
					'should set the "auto_initialize" option to false on the field "%s".',
					$child->getName()
				));
			}

			$this->children[$child->getName()] = $child;

			$child->setParent($this);

			if (!$this->lockSetData && $this->defaultDataSet && !$this->config->getInheritData()) {
				$iterator = new InheritDataAwareIterator(new \ArrayIterator(array($child->getName() => $child)));
				$iterator = new \RecursiveIteratorIterator($iterator);
				$this->config->getDataMapper()->mapDataToBlocks($viewData, $iterator);
			}

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function remove($name) {
			if (isset($this->children[$name])) {
				unset($this->children[$name]);
			}

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function has($name) {
			return isset($this->children[$name]);
		}

		/**
		 * {@inheritdoc}
		 */
		public function get($name) {
			if (isset($this->children[$name])) {
				return $this->children[$name];
			}

			throw new OutOfBoundsException(sprintf('Child "%s" does not exist.', $name));
		}

		/**
		 * Returns whether a child with the given name exists (implements the \ArrayAccess interface).
		 *
		 * @param string $name The name of the child
		 *
		 * @return bool
		 */
		public function offsetExists($name) {
			return $this->has($name);
		}

		/**
		 * Returns the child with the given name (implements the \ArrayAccess interface).
		 *
		 * @param string $name The name of the child
		 *
		 * @return BlockInterface The child block
		 *
		 * @throws \OutOfBoundsException If the named child does not exist.
		 */
		public function offsetGet($name) {
			return $this->get($name);
		}

		/**
		 * Adds a child to the block (implements the \ArrayAccess interface).
		 *
		 * @param string         $name  Ignored. The name of the child is used
		 * @param BlockInterface $child The child to be added
		 *
		 *
		 * @see self::add()
		 */
		public function offsetSet($name, $child) {
			$this->add($child);
		}

		/**
		 * Removes the child with the given name from the block (implements the \ArrayAccess interface).
		 *
		 * @param string $name The name of the child to remove
		 *
		 */
		public function offsetUnset($name) {
			$this->remove($name);
		}

		/**
		 * Returns the iterator for this group.
		 *
		 * @return \Traversable|BlockInterface[]
		 */
		public function getIterator() {
			return $this->children;
		}

		/**
		 * Returns the number of block children (implements the \Countable interface).
		 *
		 * @return int The number of embedded block children
		 */
		public function count() {
			return count($this->children);
		}

		/**
		 * {@inheritdoc}
		 */
		public function createView(BlockView $parent = null) {
			if (null === $parent && $this->parent) {
				$parent = $this->parent->createView();
			}

			$type = $this->config->getType();
			$options = $this->config->getOptions();

			// The methods createView(), buildView() and finishView() are called
			// explicitly here in order to be able to override either of them
			// in a custom resolved block type.
			$view = $type->createView($this, $parent);

			$type->buildView($view, $this, $options);

			foreach ($this->children as $name => $child) {
				$view->children[$name] = $child->createView($view);
			}

			$type->finishView($view, $this, $options);

			$include = $this->config->getBlockFactory()->getAssetInclude();
			$finder = $this->config->getBlockFactory()->getAssets();
			$isVisited = false;
			$type->assetInclude($include, $finder, $view, $isVisited);

			$rendererEngine = $this->config->getBlockFactory()->getRendererEngine();
			$blockThemeInclude = new BlockThemeInclude();
			$type->themeInclude($blockThemeInclude);
			foreach ($blockThemeInclude as $include) {
				if (!is_array($include)) {
					$include = array($include);
				}
				$rendererEngine->setTheme($view, $include);
			}


			return $view;
		}

		/**
		 * Normalizes the value if a normalization transformer is set.
		 *
		 * @param mixed $value The value to transform
		 *
		 * @return mixed
		 *
		 * @throws TransformationFailedException If the value cannot be transformed to "normalized" format
		 */
		private function modelToNorm($value) {
			try {
				foreach ($this->config->getModelTransformers() as $transformer) {
					$value = $transformer->transform($value);
				}
			} catch (TransformationFailedException $exception) {
				throw new TransformationFailedException(
					'Unable to transform value for property path "' . $this->getPropertyPath() . '": ' . $exception->getMessage(),
					$exception->getCode(),
					$exception
				);
			}

			return $value;
		}

		/**
		 * Transforms the value if a value transformer is set.
		 *
		 * @param mixed $value The value to transform
		 *
		 * @return mixed
		 *
		 * @throws TransformationFailedException If the value cannot be transformed to "view" format
		 */
		private function normToView($value) {
			try {
				foreach ($this->config->getViewTransformers() as $transformer) {
					$value = $transformer->transform($value);
				}
			} catch (TransformationFailedException $exception) {
				throw new TransformationFailedException(
					'Unable to transform value for property path "' . $this->getPropertyPath() . '": ' . $exception->getMessage(),
					$exception->getCode(),
					$exception
				);
			}

			return $value;
		}

	}
