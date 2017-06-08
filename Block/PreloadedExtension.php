<?php

	namespace Uneak\AssetsBundle\Block;

	use Uneak\AssetsBundle\Block\Exception\InvalidArgumentException;

	/**
	 * A block extension with preloaded types, type exceptions.
	 *
	 */
	class PreloadedExtension implements BlockExtensionInterface {
		/**
		 * @var BlockTypeInterface[]
		 */
		private $types = array();

		/**
		 * @var array[BlockTypeExtensionInterface[]]
		 */
		private $typeExtensions = array();

		/**
		 * Creates a new preloaded extension.
		 *
		 * @param BlockTypeInterface[]            $types          The types that the extension should support
		 * @param BlockTypeExtensionInterface[][] $typeExtensions The type extensions that the extension should support
		 */
		public function __construct(array $types, array $typeExtensions) {
			$this->typeExtensions = $typeExtensions;

			foreach ($types as $type) {
				$this->types[get_class($type)] = $type;
			}
		}

		/**
		 * {@inheritdoc}
		 */
		public function getType($name) {
			if (!isset($this->types[$name])) {
				throw new InvalidArgumentException(sprintf('The type "%s" can not be loaded by this extension', $name));
			}

			return $this->types[$name];
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasType($name) {
			return isset($this->types[$name]);
		}

		/**
		 * {@inheritdoc}
		 */
		public function getTypeExtensions($name) {
			return isset($this->typeExtensions[$name])
				? $this->typeExtensions[$name]
				: array();
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasTypeExtensions($name) {
			return !empty($this->typeExtensions[$name]);
		}

	}
