<?php

	namespace Uneak\AssetsBundle\Block;

	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Uneak\AssetsBundle\Assets\Assets;
	use Uneak\AssetsBundle\Pool\AssetInclude;

	/**
	 * A wrapper for a block type and its extensions.
	 *
	 */
	interface ResolvedBlockTypeInterface {
		/**
		 * Returns the prefix of the template block name for this type.
		 *
		 * @return string The prefix of the template block name
		 */
		public function getBlockPrefix();

		/**
		 * Returns the parent type.
		 *
		 * @return ResolvedBlockTypeInterface|null The parent type or null
		 */
		public function getParent();

		/**
		 * Returns the wrapped block type.
		 *
		 * @return BlockTypeInterface The wrapped block type
		 */
		public function getInnerType();

		/**
		 * Returns the extensions of the wrapped block type.
		 *
		 * @return BlockTypeExtensionInterface[] An array of {@link BlockTypeExtensionInterface} instances
		 */
		public function getTypeExtensions();

		/**
		 * Creates a new block builder for this type.
		 *
		 * @param BlockFactoryInterface $factory The block factory
		 * @param string               $name    The name for the builder
		 * @param array                $options The builder options
		 *
		 * @return BlockBuilderInterface The created block builder
		 */
		public function createBuilder(BlockFactoryInterface $factory, $name, array $options = array());

		/**
		 * Creates a new block view for a block of this type.
		 *
		 * @param BlockInterface $block   The block to create a view for
		 * @param BlockView      $parent The parent view or null
		 *
		 * @return BlockView The created block view
		 */
		public function createView(BlockInterface $block, BlockView $parent = null);

		/**
		 * Configures a block builder for the type hierarchy.
		 *
		 * @param BlockBuilderInterface $builder The builder to configure
		 * @param array                $options The options used for the configuration
		 */
		public function buildBlock(BlockBuilderInterface $builder, array $options);

		/**
		 * Configures a block view for the type hierarchy.
		 *
		 * It is called before the children of the view are built.
		 *
		 * @param BlockView      $view    The block view to configure
		 * @param BlockInterface $block    The block corresponding to the view
		 * @param array         $options The options used for the configuration
		 */
		public function buildView(BlockView $view, BlockInterface $block, array $options);

		/**
		 * Finishes a block view for the type hierarchy.
		 *
		 * It is called after the children of the view have been built.
		 *
		 * @param BlockView      $view    The block view to configure
		 * @param BlockInterface $block    The block corresponding to the view
		 * @param array         $options The options used for the configuration
		 */
		public function finishView(BlockView $view, BlockInterface $block, array $options);

		public function assetInclude(AssetInclude $include, Assets $assets, $parameters, $isVisited);

		public function themeInclude(BlockThemeInclude $include);

		/**
		 * Returns the configured options resolver used for this type.
		 *
		 * @return OptionsResolver The options resolver
		 */
		public function getOptionsResolver();
	}
