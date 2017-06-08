<?php

	namespace Uneak\AssetsBundle\Pool;


	use Uneak\AssetsBundle\AssetItem\Asset\Asset;

	interface PoolFactoryInterface {
		/**
		 * @param AssetInclude $includes
		 *
		 * @return Pool
		 */
		public function getPool(AssetInclude $includes);
		/**
		 * @param Asset $asset
		 *
		 * @return \Uneak\AssetsBundle\AssetType\AssetTypeInterface
		 */
		public function getAssetType(Asset $asset);
	}
