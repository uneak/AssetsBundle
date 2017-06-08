<?php

	namespace Uneak\AssetsBundle\Block;

	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Uneak\AssetsBundle\Assets\Assets;
	use Uneak\AssetsBundle\Pool\AssetInclude;

	abstract class AbstractTypeExtension implements BlockTypeExtensionInterface {

		/**
		 * {@inheritdoc}
		 */
		public function buildBlock(BlockBuilderInterface $builder, array $options) {
		}

		/**
		 * {@inheritdoc}
		 */
		public function buildView(BlockView $view, BlockInterface $block, array $options) {
		}

		/**
		 * {@inheritdoc}
		 */
		public function finishView(BlockView $view, BlockInterface $block, array $options) {
		}

		public function assetInclude(AssetInclude $include, Assets $assets, $parameters, $isVisited) {
		}

		public function themeInclude(BlockThemeInclude $include) {
		}

		/**
		 * {@inheritdoc}
		 */
		public function configureOptions(OptionsResolver $resolver) {
		}
	}
