<?php

	namespace Uneak\AssetsBundle\AssetItem\Library;
	
	use Uneak\AssetsBundle\AssetItem\TypedAssetItem;

	class Library extends TypedAssetItem {
		/**
		 * @var array
		 */
		private $main = array();
		

		/**
		 * @return array
		 */
		public function getMain() {
			return $this->main;
		}

		/**
		 * @param array $main
		 */
		public function setMain(array $main) {
			$this->main = $main;
		}


		/**
		 * @param mixed $mixed
		 *
		 * @return array
		 */
		public function merge($mixed) {
			$mixed = parent::merge($mixed);

			if (isset($mixed['main'])) {
				$this->setMain($mixed['main']);
			}

			return $mixed;
		}

		/**
		 * @return array
		 */
		public function toArray() {
			$data = parent::toArray();
			$data['main'] = $this->getMain();
			return $data;
		}

		/**
		 * {@inheritdoc}
		 */
		public function unserialize($serialized) {
			$data = parent::unserialize($serialized);
			$this->setMain($data['main']);
			return $data;
		}
	}