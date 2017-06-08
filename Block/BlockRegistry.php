<?php


	namespace Uneak\AssetsBundle\Block;

	use Uneak\AssetsBundle\Block\Exception\UnexpectedTypeException;
	use Uneak\AssetsBundle\Block\Exception\InvalidArgumentException;
	use Uneak\AssetsBundle\Exception\ExceptionInterface;

	/**
	 * The central registry of the Block component.
	 *
	 */
	class BlockRegistry implements BlockRegistryInterface {
		/**
		 * Extensions.
		 *
		 * @var BlockExtensionInterface[] An array of BlockExtensionInterface
		 */
		private $extensions = array();

		/**
		 * @var BlockTypeInterface[]
		 */
		private $types = array();

		/**
		 * @var ResolvedBlockTypeFactoryInterface
		 */
		private $resolvedTypeFactory;

		/**
		 * Constructor.
		 *
		 * @param BlockExtensionInterface[]         $extensions          An array of BlockExtensionInterface
		 * @param ResolvedBlockTypeFactoryInterface $resolvedTypeFactory The factory for resolved Block types
		 *
		 * @throws UnexpectedTypeException if any extension does not implement BlockExtensionInterface
		 */
		public function __construct(array $extensions, ResolvedBlockTypeFactoryInterface $resolvedTypeFactory) {
			foreach ($extensions as $extension) {
				if (!$extension instanceof BlockExtensionInterface) {
					throw new UnexpectedTypeException($extension, 'Uneak\AssetsBundle\Block\BlockExtensionInterface');
				}
			}

			$this->extensions = $extensions;
			$this->resolvedTypeFactory = $resolvedTypeFactory;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getType($name) {
			if (!isset($this->types[$name])) {
				$type = null;

				foreach ($this->extensions as $extension) {
					if ($extension->hasType($name)) {
						$type = $extension->getType($name);
						break;
					}
				}

				if (!$type) {
					// Support fully-qualified class names
					if (class_exists($name) && in_array('Uneak\AssetsBundle\Block\BlockTypeInterface', class_implements($name))) {
						$type = new $name();
					} else {
						throw new InvalidArgumentException(sprintf('Could not load type "%s"', $name));
					}
				}

				$this->types[$name] = $this->resolveType($type);
			}

			return $this->types[$name];
		}

		/**
		 * Wraps a type into a ResolvedBlockTypeInterface implementation and connects
		 * it with its parent type.
		 *
		 * @param BlockTypeInterface $type The type to resolve
		 *
		 * @return ResolvedBlockTypeInterface The resolved type
		 */
		private function resolveType(BlockTypeInterface $type) {
			$typeExtensions = array();
			$parentType = $type->getParent();
			$fqcn = get_class($type);

			foreach ($this->extensions as $extension) {
				$typeExtensions = array_merge(
					$typeExtensions,
					$extension->getTypeExtensions($fqcn)
				);
			}

			return $this->resolvedTypeFactory->createResolvedType(
				$type,
				$typeExtensions,
				$parentType ? $this->getType($parentType) : null
			);
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasType($name) {
			if (isset($this->types[$name])) {
				return true;
			}

			try {
				$this->getType($name);
			} catch (ExceptionInterface $e) {
				return false;
			}

			return true;
		}


		/**
		 * {@inheritdoc}
		 */
		public function getExtensions() {
			return $this->extensions;
		}
	}
