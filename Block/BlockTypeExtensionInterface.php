<?php

	namespace Uneak\AssetsBundle\Block;

	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Uneak\AssetsBundle\Assets\Assets;
	use Uneak\AssetsBundle\Pool\AssetInclude;

	interface BlockTypeExtensionInterface {
		/**
		 * Builds the block.
		 *
		 * This method is called after the extended type has built the block to
		 * further modify it.
		 *
		 * @see BlockTypeInterface::buildBlock()
		 *
		 * @param BlockBuilderInterface $builder The block builder
		 * @param array                $options The options
		 */
		public function buildBlock(BlockBuilderInterface $builder, array $options);

		/**
		 * Builds the view.
		 *
		 * This method is called after the extended type has built the view to
		 * further modify it.
		 *
		 * @see BlockTypeInterface::buildView()
		 *
		 * @param BlockView      $view    The view
		 * @param BlockInterface $block    The block
		 * @param array         $options The options
		 */
		public function buildView(BlockView $view, BlockInterface $block, array $options);

		/**
		 * Finishes the view.
		 *
		 * This method is called after the extended type has finished the view to
		 * further modify it.
		 *
		 * @see BlockTypeInterface::finishView()
		 *
		 * @param BlockView      $view    The view
		 * @param BlockInterface $block    The block
		 * @param array         $options The options
		 */
		public function finishView(BlockView $view, BlockInterface $block, array $options);

		public function assetInclude(AssetInclude $include, Assets $assets, $parameters, $isVisited);

		public function themeInclude(BlockThemeInclude $include);

		/**
		 * Configures the options for this type.
		 *
		 * @param OptionsResolver $resolver The resolver for the options
		 */
		public function configureOptions(OptionsResolver $resolver);

		/**
		 * Returns the name of the type being extended.
		 *
		 * @return string The name of the type being extended
		 */
		public function getExtendedType();
	}
