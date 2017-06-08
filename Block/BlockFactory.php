<?php

	namespace Uneak\AssetsBundle\Block;

	use Uneak\AssetsBundle\Assets\Assets;
	use Uneak\AssetsBundle\Block\Exception\UnexpectedTypeException;
	use Uneak\AssetsBundle\Pool\AssetInclude;

	class BlockFactory implements BlockFactoryInterface {
		/**
		 * @var BlockRegistryInterface
		 */
		private $registry;

		/**
		 * @var ResolvedBlockTypeFactoryInterface
		 */
		private $resolvedTypeFactory;
		/**
		 * @var \Uneak\AssetsBundle\Pool\AssetInclude
		 */
		private $assetInclude;
		/**
		 * @var Assets
		 */
		private $assets;
		/**
		 * @var AbstractRendererEngine
		 */
		private $rendererEngine;

		public function __construct(BlockRegistryInterface $registry, ResolvedBlockTypeFactoryInterface $resolvedTypeFactory, AssetInclude $assetInclude, Assets $assets, AbstractRendererEngine $rendererEngine) {
			$this->registry = $registry;
			$this->resolvedTypeFactory = $resolvedTypeFactory;
			$this->assetInclude = $assetInclude;
			$this->assets = $assets;
			$this->rendererEngine = $rendererEngine;
		}

		/**
		 * @return AbstractRendererEngine
		 */
		public function getRendererEngine() {
			return $this->rendererEngine;
		}

		/**
		 * @return \Uneak\AssetsBundle\Pool\AssetInclude
		 */
		public function getAssetInclude() {
			return $this->assetInclude;
		}

		/**
		 * @return Assets
		 */
		public function getAssets() {
			return $this->assets;
		}



		/**
		 * {@inheritdoc}
		 */
		public function create($type = 'Uneak\AssetsBundle\Block\Extension\Core\Type\BlockType', $data = null, array $options = array()) {
			return $this->createBuilder($type, $data, $options)->getBlock();
		}

		/**
		 * {@inheritdoc}
		 */
		public function createNamed($name, $type = 'Uneak\AssetsBundle\Block\Extension\Core\Type\BlockType', $data = null, array $options = array()) {
			return $this->createNamedBuilder($name, $type, $data, $options)->getBlock();
		}

		/**
		 * {@inheritdoc}
		 */
		public function createForProperty($class, $property, $data = null, array $options = array()) {
			return $this->createBuilderForProperty($class, $property, $data, $options)->getBlock();
		}

		/**
		 * {@inheritdoc}
		 */
		public function createBuilder($type = 'Uneak\AssetsBundle\Block\Extension\Core\Type\BlockType', $data = null, array $options = array()) {
			if (!is_string($type)) {
				throw new UnexpectedTypeException($type, 'string');
			}

			return $this->createNamedBuilder($this->registry->getType($type)->getBlockPrefix(), $type, $data, $options);
		}

		/**
		 * {@inheritdoc}
		 */
		public function createNamedBuilder($name, $type = 'Uneak\AssetsBundle\Block\Extension\Core\Type\BlockType', $data = null, array $options = array()) {
			if (null !== $data && !array_key_exists('data', $options)) {
				$options['data'] = $data;
			}

			if (!is_string($type)) {
				throw new UnexpectedTypeException($type, 'string');
			}

			$type = $this->registry->getType($type);

			$builder = $type->createBuilder($this, $name, $options);

			// Explicitly call buildBlock() in order to be able to override either
			// createBuilder() or buildBlock() in the resolved block type
			$type->buildBlock($builder, $builder->getOptions());

			return $builder;
		}

		/**
		 * {@inheritdoc}
		 */
		public function createBuilderForProperty($class, $property, $data = null, array $options = array()) {
			return $this->createNamedBuilder($property, 'Uneak\AssetsBundle\Block\Extension\Core\Type\TextType', $data, $options);
		}
	}
