<?php

	namespace Uneak\AssetsBundle\Block\Extension\Core\Type;

	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Uneak\AssetsBundle\Block\AbstractType;
	use Uneak\AssetsBundle\Block\BlockBuilderInterface;
	use Uneak\AssetsBundle\Block\BlockInterface;
	use Uneak\AssetsBundle\Block\BlockView;

	abstract class BaseType extends AbstractType {
		/**
		 * {@inheritdoc}
		 */
		public function buildBlock(BlockBuilderInterface $builder, array $options) {
			$builder->setAutoInitialize($options['auto_initialize']);
		}

		/**
		 * {@inheritdoc}
		 */
		public function buildView(BlockView $view, BlockInterface $block, array $options) {
			$name = $block->getName();
			$blockName = $options['block_name'] ?: $block->getName();
			$translationDomain = $options['translation_domain'];

			if ($view->parent) {
				if ('' !== $view->parent->vars['id']) {
					$id = sprintf('%s_%s', $view->parent->vars['id'], $name);
					$uniqueBlockPrefix = sprintf('%s_%s', $view->parent->vars['unique_block_prefix'], $blockName);
				} else {
					$id = $name;
					$uniqueBlockPrefix = '_' . $blockName;
				}

				if (null === $translationDomain) {
					$translationDomain = $view->parent->vars['translation_domain'];
				}

			} else {
				$id = $name;
				$uniqueBlockPrefix = '_' . $blockName;

				// Strip leading underscores and digits. These are allowed in
				// block names, but not in HTML4 ID attributes.
				// http://www.w3.org/TR/html401/struct/global.html#adef-id
				$id = ltrim($id, '_0123456789');
			}

			$blockPrefixes = array();
			for ($type = $block->getConfig()->getType(); null !== $type; $type = $type->getParent()) {
				array_unshift($blockPrefixes, $type->getBlockPrefix());
			}
			$blockPrefixes[] = $uniqueBlockPrefix;

			$view->vars = array_replace($view->vars, array(
				'block'                => $view,
				'id'                  => $id,
				'name'                => $name,
				'attr'                => $options['attr'],
				'block_prefixes'      => $blockPrefixes,
				'unique_block_prefix' => $uniqueBlockPrefix,
				'translation_domain'  => $translationDomain,
				// Using the block name here speeds up performance in collection
				// blocks, where each entry has the same full block name.
				// Including the type is important too, because if rows of a
				// collection block have different types (dynamically), they should
				// be rendered differently.
				// https://github.com/symfony/symfony/issues/5038
				'cache_key'           => $uniqueBlockPrefix . '_' . $block->getConfig()->getType()->getBlockPrefix(),
			));
		}

		/**
		 * {@inheritdoc}
		 */
		public function configureOptions(OptionsResolver $resolver) {
			$resolver->setDefaults(array(
				'block_name'         => null,
				'attr'               => array(),
				'translation_domain' => null,
				'auto_initialize'    => true,
			));

			$resolver->setAllowedTypes('attr', 'array');
		}
	}
