<?php

	namespace Uneak\AssetsBundle\AssetItem;

	use Uneak\AssetsBundle\Exception\UnmergeableException;
	use Symfony\Component\Config\Resource\ResourceInterface;

	abstract class TypedAssetItem extends AssetItem implements TypedAssetItemInterface {
		/**
		 * @var string
		 */
		private $type;
		/**
		 * @var array
		 */
		private $dependencies = array();


		/**
		 * @return string
		 */
		public function getType() {
			return $this->type;
		}

		/**
		 * @param string $type
		 */
		public function setType($type) {
			$this->type = $type;
		}

		/**
		 * @return array
		 */
		public function getDependencies() {
			return $this->dependencies;
		}

		/**
		 * @param array $dependencies
		 */
		public function setDependencies(array $dependencies) {
			$this->dependencies = $dependencies;
		}
		

		/**
		 * @param mixed $mixed
		 *
		 * @return array
		 */
		public function merge($mixed) {
			$mixed = parent::merge($mixed);
			if (isset($mixed['dependencies'])) {
				$this->setDependencies($mixed['dependencies']);
			}
			if (isset($mixed['type'])) {
				$this->setType($mixed['type']);
			}
			return $mixed;
		}

		/**
		 * @return array
		 */
		public function toArray() {
			$data = parent::toArray();
			$data['dependencies'] = $this->getDependencies();
			$data['type'] = $this->getType();
			return $data;
		}

		/**
		 * {@inheritdoc}
		 */
		public function unserialize($serialized) {
			$data = parent::unserialize($serialized);
			$this->setDependencies($data['dependencies']);
			$this->setType($data['type']);
			return $data;
		}
		
	}