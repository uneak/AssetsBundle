<?php

	namespace Uneak\AssetsBundle\Block;

	/**
	 * The default implementation of BlockFactoryBuilderInterface.
	 *
	 */
	class BlockFactoryBuilder implements BlockFactoryBuilderInterface {
		/**
		 * @var ResolvedBlockTypeFactoryInterface
		 */
		private $resolvedTypeFactory;

		/**
		 * @var BlockExtensionInterface[]
		 */
		private $extensions = array();

		/**
		 * @var BlockTypeInterface[]
		 */
		private $types = array();

		/**
		 * @var BlockTypeExtensionInterface[]
		 */
		private $typeExtensions = array();

		/**
		 * {@inheritdoc}
		 */
		public function setResolvedTypeFactory(ResolvedBlockTypeFactoryInterface $resolvedTypeFactory) {
			$this->resolvedTypeFactory = $resolvedTypeFactory;

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function addExtension(BlockExtensionInterface $extension) {
			$this->extensions[] = $extension;

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function addExtensions(array $extensions) {
			$this->extensions = array_merge($this->extensions, $extensions);

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function addType(BlockTypeInterface $type) {
			$this->types[] = $type;

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function addTypes(array $types) {
			foreach ($types as $type) {
				$this->types[] = $type;
			}

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function addTypeExtension(BlockTypeExtensionInterface $typeExtension) {
			$this->typeExtensions[$typeExtension->getExtendedType()][] = $typeExtension;

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function addTypeExtensions(array $typeExtensions) {
			foreach ($typeExtensions as $typeExtension) {
				$this->typeExtensions[$typeExtension->getExtendedType()][] = $typeExtension;
			}

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getBlockFactory() {
			$extensions = $this->extensions;

			if (count($this->types) > 0 || count($this->typeExtensions) > 0) {
				$extensions[] = new PreloadedExtension($this->types, $this->typeExtensions);
			}

			$resolvedTypeFactory = $this->resolvedTypeFactory ?: new ResolvedBlockTypeFactory();
			$registry = new BlockRegistry($extensions, $resolvedTypeFactory);

			return new BlockFactory($registry, $resolvedTypeFactory);
		}
	}
