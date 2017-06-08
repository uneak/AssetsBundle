<?php

	namespace Uneak\AssetsBundle\Block\Extension\Core\DataMapper;

	use Uneak\AssetsBundle\Block\Exception\UnexpectedTypeException;
	use Symfony\Component\PropertyAccess\PropertyAccess;
	use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
	use Uneak\AssetsBundle\Block\DataMapperInterface;

	/**
	 * Maps arrays/objects to/from blocks using property paths.
	 *
	 */
	class PropertyPathMapper implements DataMapperInterface {
		/**
		 * @var PropertyAccessorInterface
		 */
		private $propertyAccessor;

		/**
		 * Creates a new property path mapper.
		 *
		 * @param PropertyAccessorInterface $propertyAccessor The property accessor
		 */
		public function __construct(PropertyAccessorInterface $propertyAccessor = null) {
			$this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
		}

		/**
		 * {@inheritdoc}
		 */
		public function mapDataToBlocks($data, $blocks) {
			$empty = null === $data || array() === $data;

			if (!$empty && !is_array($data) && !is_object($data)) {
				throw new UnexpectedTypeException($data, 'object, array or empty');
			}

			foreach ($blocks as $block) {
				$propertyPath = $block->getPropertyPath();
				$config = $block->getConfig();

				if (!$empty && null !== $propertyPath) {
					$block->setData($this->propertyAccessor->getValue($data, $propertyPath));
				} else {
					$block->setData($block->getConfig()->getData());
				}
			}
		}

	}
