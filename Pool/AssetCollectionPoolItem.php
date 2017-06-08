<?php

	namespace Uneak\AssetsBundle\Pool;

	use Uneak\AssetsBundle\AssetItem\Asset\Asset;
	use Uneak\AssetsBundle\AssetItem\AssetItemCollection;
	use Uneak\AssetsBundle\AssetItem\AssetItemCollectionInterface;

	class AssetCollectionPoolItem extends PoolItem {

		public function __construct($id, array $dependencies = array(), AssetItemCollectionInterface $data = null) {
			$data = ($data) ?: new AssetItemCollection();
			parent::__construct($id, $dependencies, $data);
		}

		public function getDependencies() {
			$dependencies = $this->dependencies;
			/** @var $asset Asset */
			foreach ($this->data->all() as $asset) {
				$dependencies = array_merge($dependencies, $asset->getDependencies());
			}
			return array_unique($dependencies);
		}


		public function merge($mixed) {
			/** @var $asset Asset */
			if ($mixed instanceof AssetCollectionPoolItem) {
				foreach ($mixed->data->all() as $asset) {
					$this->getData()->add($asset);
				}

			} else if ($mixed instanceof AssetPoolItem) {
				$asset = $mixed->getData();
				$this->getData()->add($asset);
				return $asset;

			} else if ($mixed instanceof Asset) {
				$this->getData()->add($mixed);
				return $mixed;

			} else if (is_array($mixed)) {
				return $mixed;
			}

			return null;
		}

	}