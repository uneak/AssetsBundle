<?php

	namespace Uneak\AssetsBundle\Pool;

	use Uneak\AssetsBundle\AssetItem\Asset\Asset;

	class AssetPoolItem extends PoolItem {

		public function __construct($id, array $dependencies = array(), Asset $data = null) {
			parent::__construct($id, $dependencies, $data);
		}

		public function getDependencies() {
			return array_unique(array_merge($this->dependencies, $this->data->getDependencies()));
		}


		public function merge($mixed) {
			if ($mixed instanceof AssetPoolItem) {
				return $this->data->merge($mixed->getData());

			} else if ($mixed instanceof Asset) {
				return $this->data->merge($mixed);

			} else if (is_array($mixed)) {
				return $this->data->merge($mixed);
			}

			return null;
		}

	}