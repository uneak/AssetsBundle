<?php

	namespace Uneak\AssetsBundle\Block\Extension\Core\Type;

	use Symfony\Component\OptionsResolver\Options;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\PropertyAccess\PropertyAccess;
	use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
	use Uneak\AssetsBundle\Block\BlockBuilderInterface;
	use Uneak\AssetsBundle\Block\BlockInterface;
	use Uneak\AssetsBundle\Block\BlockView;
	use Uneak\AssetsBundle\Block\Exception\LogicException;
	use Uneak\AssetsBundle\Block\Extension\Core\DataMapper\PropertyPathMapper;

	class BlockType extends BaseType {
		/**
		 * @var PropertyAccessorInterface
		 */
		private $propertyAccessor;

		public function __construct(PropertyAccessorInterface $propertyAccessor = null) {
			$this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
		}

		/**
		 * {@inheritdoc}
		 */
		public function buildBlock(BlockBuilderInterface $builder, array $options) {
			parent::buildBlock($builder, $options);

			$isDataOptionSet = array_key_exists('data', $options);

			$builder
				->setEmptyData($options['empty_data'])
				->setPropertyPath($options['property_path'])
				->setInheritData($options['inherit_data'])
				->setData($isDataOptionSet ? $options['data'] : null)
				->setDataLocked($isDataOptionSet)
				->setDataMapper(new PropertyPathMapper($this->propertyAccessor))
			;

		}

		/**
		 * {@inheritdoc}
		 */
		public function buildView(BlockView $view, BlockInterface $block, array $options) {
			parent::buildView($view, $block, $options);

			$name = $block->getName();

			if ($view->parent) {
				if ('' === $name) {
					throw new LogicException('Block node with empty name can be used only as root block node.');
				}
			}

			$view->vars = array_replace($view->vars, array(
				'value'      => $block->getViewData(),
				'data'       => $block->getNormData(),
			));
		}

		/**
		 * {@inheritdoc}
		 */
		public function finishView(BlockView $view, BlockInterface $block, array $options) {
		}

		/**
		 * {@inheritdoc}
		 */
		public function configureOptions(OptionsResolver $resolver) {
			parent::configureOptions($resolver);

			// Derive "data_class" option from passed "data" object
			$dataClass = function (Options $options) {
				return isset($options['data']) && is_object($options['data']) ? get_class($options['data']) : null;
			};

			// Derive "empty_data" closure from "data_class" option
			$emptyData = function (Options $options) {
				$class = $options['data_class'];

				if (null !== $class) {
					return function (BlockInterface $block) use ($class) {
						return $block->isEmpty() ? null : new $class();
					};
				}

				return function (BlockInterface $block) {
					return $block->getConfig()->getCompound() ? array() : '';
				};
			};

			// If data is given, the block is locked to that data
			// (independent of its value)
			$resolver->setDefined(array(
				'data',
			));

			$resolver->setDefaults(array(
				'data_class' => $dataClass,
				'empty_data' => $emptyData,
				'property_path'         => null,
				'inherit_data'          => false,
				'attr'                  => array(),
			));

		}

		/**
		 * {@inheritdoc}
		 */
		public function getParent() {
		}

		/**
		 * {@inheritdoc}
		 */
		public function getBlockPrefix() {
			return 'block';
		}
	}
