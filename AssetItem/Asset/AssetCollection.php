<?php

	namespace Uneak\AssetsBundle\AssetItem\Asset;

	use Uneak\AssetsBundle\AssetItem\AssetItemCollection;
	use Uneak\AssetsBundle\Exception\AssetNotFoundException;
	use Uneak\AssetsBundle\Exception\NotFoundException;

	class AssetCollection extends AssetItemCollection {

		/**
		 * @param $name
		 *
		 * @return \Uneak\AssetsBundle\AssetItem\Asset\Asset
		 * @throws \Uneak\AssetsBundle\Exception\AssetNotFoundException
		 */
		public function get($name) {
			try {
				$asset = parent::get($name);
			} catch (NotFoundException $e) {
				throw new AssetNotFoundException(sprintf('Asset not found %s', $name));
			}

			return $asset;
		}
		
	}