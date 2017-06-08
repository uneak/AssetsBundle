<?php

	namespace Uneak\AssetsBundle\Block;

	use Uneak\AssetsBundle\Assets\Assets;
	use Uneak\AssetsBundle\Block\Util\StringUtil;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Uneak\AssetsBundle\Block\Extension\Core\Type\BlockType;
	use Uneak\AssetsBundle\Pool\AssetInclude;

	abstract class AbstractType implements BlockTypeInterface {

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

		/**
		 * {@inheritdoc}
		 */
		public function getBlockPrefix() {
			return StringUtil::fqcnToBlockPrefix(get_class($this));
		}

		/**
		 * {@inheritdoc}
		 */
		public function getParent() {
			return BlockType::class;
		}
	}
