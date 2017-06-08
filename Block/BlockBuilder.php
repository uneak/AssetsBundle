<?php

	namespace Uneak\AssetsBundle\Block;

	use Uneak\AssetsBundle\Block\Exception\BadMethodCallException;
	use Uneak\AssetsBundle\Block\Exception\InvalidArgumentException;
	use Uneak\AssetsBundle\Block\Exception\UnexpectedTypeException;
	use Symfony\Component\EventDispatcher\EventDispatcherInterface;
	use Uneak\AssetsBundle\Block\Extension\Core\Type\BlockType;

	/**
	 * A builder for creating {@link Block} instances.
	 *
	 * @author Bernhard Schussek <bschussek@gmail.com>
	 */
	class BlockBuilder extends BlockConfigBuilder implements \IteratorAggregate, BlockBuilderInterface {
		/**
		 * The children of the block builder.
		 *
		 * @var BlockBuilderInterface[]
		 */
		private $children = array();

		/**
		 * The data of children who haven't been converted to block builders yet.
		 *
		 * @var array
		 */
		private $unresolvedChildren = array();

		/**
		 * Creates a new block builder.
		 *
		 * @param string                   $name
		 * @param string                   $dataClass
		 * @param EventDispatcherInterface $dispatcher
		 * @param BlockFactoryInterface     $factory
		 * @param array                    $options
		 */
		public function __construct($name, $dataClass, EventDispatcherInterface $dispatcher, BlockFactoryInterface $factory, array $options = array()) {
			parent::__construct($name, $dataClass, $dispatcher, $options);

			$this->setBlockFactory($factory);
		}

		/**
		 * {@inheritdoc}
		 */
		public function add($child, $type = null, array $options = array()) {
			if ($this->locked) {
				throw new BadMethodCallException('BlockBuilder methods cannot be accessed anymore once the builder is turned into a BlockConfigInterface instance.');
			}

			if ($child instanceof BlockBuilderInterface) {
				$this->children[$child->getName()] = $child;

				// In case an unresolved child with the same name exists
				unset($this->unresolvedChildren[$child->getName()]);

				return $this;
			}

			if (!is_string($child) && !is_int($child)) {
				throw new UnexpectedTypeException($child, 'string, integer or Uneak\AssetsBundle\Block\BlockBuilderInterface');
			}

			if (null !== $type && !is_string($type) && !$type instanceof BlockTypeInterface) {
				throw new UnexpectedTypeException($type, 'string or Uneak\AssetsBundle\Block\BlockTypeInterface');
			}

			// Add to "children" to maintain order
			$this->children[$child] = null;
			$this->unresolvedChildren[$child] = array(
				'type'    => $type,
				'options' => $options,
			);

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function create($name, $type = null, array $options = array()) {
			if ($this->locked) {
				throw new BadMethodCallException('BlockBuilder methods cannot be accessed anymore once the builder is turned into a BlockConfigInterface instance.');
			}

			if (null === $type && null === $this->getDataClass()) {
				$type = BlockType::class;
			}

			if (null !== $type) {
				return $this->getBlockFactory()->createNamedBuilder($name, $type, null, $options);
			}

			return $this->getBlockFactory()->createBuilderForProperty($this->getDataClass(), $name, null, $options);
		}

		/**
		 * {@inheritdoc}
		 */
		public function get($name) {
			if ($this->locked) {
				throw new BadMethodCallException('BlockBuilder methods cannot be accessed anymore once the builder is turned into a BlockConfigInterface instance.');
			}

			if (isset($this->unresolvedChildren[$name])) {
				return $this->resolveChild($name);
			}

			if (isset($this->children[$name])) {
				return $this->children[$name];
			}

			throw new InvalidArgumentException(sprintf('The child with the name "%s" does not exist.', $name));
		}

		/**
		 * {@inheritdoc}
		 */
		public function remove($name) {
			if ($this->locked) {
				throw new BadMethodCallException('BlockBuilder methods cannot be accessed anymore once the builder is turned into a BlockConfigInterface instance.');
			}

			unset($this->unresolvedChildren[$name], $this->children[$name]);

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function has($name) {
			if ($this->locked) {
				throw new BadMethodCallException('BlockBuilder methods cannot be accessed anymore once the builder is turned into a BlockConfigInterface instance.');
			}

			if (isset($this->unresolvedChildren[$name])) {
				return true;
			}

			if (isset($this->children[$name])) {
				return true;
			}

			return false;
		}

		/**
		 * {@inheritdoc}
		 */
		public function all() {
			if ($this->locked) {
				throw new BadMethodCallException('BlockBuilder methods cannot be accessed anymore once the builder is turned into a BlockConfigInterface instance.');
			}

			$this->resolveChildren();

			return $this->children;
		}

		/**
		 * {@inheritdoc}
		 */
		public function count() {
			if ($this->locked) {
				throw new BadMethodCallException('BlockBuilder methods cannot be accessed anymore once the builder is turned into a BlockConfigInterface instance.');
			}

			return count($this->children);
		}

		/**
		 * {@inheritdoc}
		 */
		public function getBlockConfig() {
			/** @var $config self */
			$config = parent::getBlockConfig();

			$config->children = array();
			$config->unresolvedChildren = array();

			return $config;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getBlock() {
			if ($this->locked) {
				throw new BadMethodCallException('BlockBuilder methods cannot be accessed anymore once the builder is turned into a BlockConfigInterface instance.');
			}

			$this->resolveChildren();

			$block = new Block($this->getBlockConfig());

			foreach ($this->children as $child) {
				// Automatic initialization is only supported on root blocks
				$block->add($child->setAutoInitialize(false)->getBlock());
			}

			if ($this->getAutoInitialize()) {
				// Automatically initialize the block if it is configured so
				$block->initialize();
			}

			return $block;
		}

		/**
		 * {@inheritdoc}
		 *
		 * @return BlockBuilderInterface[]
		 */
		public function getIterator() {
			if ($this->locked) {
				throw new BadMethodCallException('BlockBuilder methods cannot be accessed anymore once the builder is turned into a BlockConfigInterface instance.');
			}

			return new \ArrayIterator($this->all());
		}

		/**
		 * Converts an unresolved child into a {@link BlockBuilder} instance.
		 *
		 * @param string $name The name of the unresolved child
		 *
		 * @return BlockBuilder The created instance
		 */
		private function resolveChild($name) {
			$info = $this->unresolvedChildren[$name];
			$child = $this->create($name, $info['type'], $info['options']);
			$this->children[$name] = $child;
			unset($this->unresolvedChildren[$name]);

			return $child;
		}

		/**
		 * Converts all unresolved children into {@link BlockBuilder} instances.
		 */
		private function resolveChildren() {
			foreach ($this->unresolvedChildren as $name => $info) {
				$this->children[$name] = $this->create($name, $info['type'], $info['options']);
			}

			$this->unresolvedChildren = array();
		}
	}
