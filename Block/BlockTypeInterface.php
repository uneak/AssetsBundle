<?php

	namespace Uneak\AssetsBundle\Block;

	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Uneak\AssetsBundle\Assets\Assets;
	use Uneak\AssetsBundle\Pool\AssetInclude;

	interface BlockTypeInterface {
		/**
		 * Builds the block.
		 *
		 * This method is called for each type in the hierarchy starting from the
		 * top most type. Type extensions can further modify the block.
		 *
		 * @see BlockTypeExtensionInterface::buildBlock()
		 *
		 * @param BlockBuilderInterface $builder The block builder
		 * @param array                $options The options
		 */
		public function buildBlock(BlockBuilderInterface $builder, array $options);

		/**
		 * Builds the block view.
		 *
		 * This method is called for each type in the hierarchy starting from the
		 * top most type. Type extensions can further modify the view.
		 *
		 * A view of a block is built before the views of the child blocks are built.
		 * This means that you cannot access child views in this method. If you need
		 * to do so, move your logic to {@link finishView()} instead.
		 *
		 * @see BlockTypeExtensionInterface::buildView()
		 *
		 * @param BlockView      $view    The view
		 * @param BlockInterface $block    The block
		 * @param array         $options The options
		 */
		public function buildView(BlockView $view, BlockInterface $block, array $options);

		/**
		 * Finishes the block view.
		 *
		 * This method gets called for each type in the hierarchy starting from the
		 * top most type. Type extensions can further modify the view.
		 *
		 * When this method is called, views of the block's children have already
		 * been built and finished and can be accessed. You should only implement
		 * such logic in this method that actually accesses child views. For everything
		 * else you are recommended to implement {@link buildView()} instead.
		 *
		 * @see BlockTypeExtensionInterface::finishView()
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
		 * Returns the prefix of the template block name for this type.
		 *
		 * The block prefix defaults to the underscored short class name with
		 * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
		 *
		 * @return string The prefix of the template block name
		 */
		public function getBlockPrefix();

		/**
		 * Returns the name of the parent type.
		 *
		 * @return string|null The name of the parent type if any, null otherwise
		 */
		public function getParent();
	}
