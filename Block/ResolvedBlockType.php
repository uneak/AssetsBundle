<?php

	namespace Uneak\AssetsBundle\Block;

	use Uneak\AssetsBundle\Assets\Assets;
	use Uneak\AssetsBundle\Block\Exception\UnexpectedTypeException;
	use Symfony\Component\EventDispatcher\EventDispatcher;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Uneak\AssetsBundle\Pool\AssetInclude;

	/**
	 * A wrapper for a Block type and its extensions.
	 *
	 */
	class ResolvedBlockType implements ResolvedBlockTypeInterface {
		/**
		 * @var BlockTypeInterface
		 */
		private $innerType;

		/**
		 * @var BlockTypeExtensionInterface[]
		 */
		private $typeExtensions;

		/**
		 * @var ResolvedBlockTypeInterface|null
		 */
		private $parent;

		/**
		 * @var OptionsResolver
		 */
		private $optionsResolver;

		public function __construct(BlockTypeInterface $innerType, array $typeExtensions = array(), ResolvedBlockTypeInterface $parent = null) {
			foreach ($typeExtensions as $extension) {
				if (!$extension instanceof BlockTypeExtensionInterface) {
					throw new UnexpectedTypeException($extension, 'Uneak\AssetsBundle\Block\BlockTypeExtensionInterface');
				}
			}

			$this->innerType = $innerType;
			$this->typeExtensions = $typeExtensions;
			$this->parent = $parent;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getBlockPrefix() {
			return $this->innerType->getBlockPrefix();
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
		public function getInnerType() {
			return $this->innerType;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getTypeExtensions() {
			return $this->typeExtensions;
		}

		/**
		 * {@inheritdoc}
		 */
		public function createBuilder(BlockFactoryInterface $factory, $name, array $options = array()) {
			$options = $this->getOptionsResolver()->resolve($options);

			// Should be decoupled from the specific option at some point
			$dataClass = isset($options['data_class']) ? $options['data_class'] : null;

			$builder = $this->newBuilder($name, $dataClass, $factory, $options);
			$builder->setType($this);

			return $builder;
		}

		/**
		 * {@inheritdoc}
		 */
		public function createView(BlockInterface $Block, BlockView $parent = null) {
			return $this->newView($parent);
		}

		/**
		 * Configures a Block builder for the type hierarchy.
		 *
		 * @param BlockBuilderInterface $builder The builder to configure
		 * @param array                $options The options used for the configuration
		 */
		public function buildBlock(BlockBuilderInterface $builder, array $options) {
			if (null !== $this->parent) {
				$this->parent->buildBlock($builder, $options);
			}

			$this->innerType->buildBlock($builder, $options);

			foreach ($this->typeExtensions as $extension) {
				$extension->buildBlock($builder, $options);
			}
		}

		/**
		 * Configures a Block view for the type hierarchy.
		 *
		 * This method is called before the children of the view are built.
		 *
		 * @param BlockView      $view    The Block view to configure
		 * @param BlockInterface $Block    The Block corresponding to the view
		 * @param array         $options The options used for the configuration
		 */
		public function buildView(BlockView $view, BlockInterface $Block, array $options) {
			if (null !== $this->parent) {
				$this->parent->buildView($view, $Block, $options);
			}

			$this->innerType->buildView($view, $Block, $options);

			foreach ($this->typeExtensions as $extension) {
				$extension->buildView($view, $Block, $options);
			}
		}

		/**
		 * Finishes a Block view for the type hierarchy.
		 *
		 * This method is called after the children of the view have been built.
		 *
		 * @param BlockView      $view    The Block view to configure
		 * @param BlockInterface $Block    The Block corresponding to the view
		 * @param array         $options The options used for the configuration
		 */
		public function finishView(BlockView $view, BlockInterface $Block, array $options) {
			if (null !== $this->parent) {
				$this->parent->finishView($view, $Block, $options);
			}

			$this->innerType->finishView($view, $Block, $options);

			foreach ($this->typeExtensions as $extension) {
				/* @var BlockTypeExtensionInterface $extension */
				$extension->finishView($view, $Block, $options);
			}
		}


		public function assetInclude(AssetInclude $include, Assets $assets, $parameters, $isVisited) {
			if (null !== $this->parent) {
				$this->parent->assetInclude($include, $assets, $parameters, $isVisited);
			}

			$this->innerType->assetInclude($include, $assets, $parameters, $isVisited);

			foreach ($this->typeExtensions as $extension) {
				/* @var BlockTypeExtensionInterface $extension */
				$extension->assetInclude($include, $assets, $parameters, $isVisited);
			}
		}

		public function themeInclude(BlockThemeInclude $include) {
			if (null !== $this->parent) {
				$this->parent->themeInclude($include);
			}

			$this->innerType->themeInclude($include);

			foreach ($this->typeExtensions as $extension) {
				/* @var BlockTypeExtensionInterface $extension */
				$extension->themeInclude($include);
			}
		}


		/**
		 * Returns the configured options resolver used for this type.
		 *
		 * @return \Symfony\Component\OptionsResolver\OptionsResolver The options resolver
		 */
		public function getOptionsResolver() {
			if (null === $this->optionsResolver) {
				if (null !== $this->parent) {
					$this->optionsResolver = clone $this->parent->getOptionsResolver();
				} else {
					$this->optionsResolver = new OptionsResolver();
				}

				$this->innerType->configureOptions($this->optionsResolver);

				foreach ($this->typeExtensions as $extension) {
					$extension->configureOptions($this->optionsResolver);
				}
			}

			return $this->optionsResolver;
		}

		/**
		 * Creates a new builder instance.
		 *
		 * Override this method if you want to customize the builder class.
		 *
		 * @param string               $name      The name of the builder
		 * @param string               $dataClass The data class
		 * @param BlockFactoryInterface $factory   The current Block factory
		 * @param array                $options   The builder options
		 *
		 * @return BlockBuilderInterface The new builder instance
		 */
		protected function newBuilder($name, $dataClass, BlockFactoryInterface $factory, array $options) {
			return new BlockBuilder($name, $dataClass, new EventDispatcher(), $factory, $options);
		}

		/**
		 * Creates a new view instance.
		 *
		 * Override this method if you want to customize the view class.
		 *
		 * @param BlockView|null $parent The parent view, if available
		 *
		 * @return BlockView A new view instance
		 */
		protected function newView(BlockView $parent = null) {
			return new BlockView($parent);
		}
	}
