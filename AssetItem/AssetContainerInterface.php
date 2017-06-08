<?php

	namespace Uneak\AssetsBundle\AssetItem;
	
	use Uneak\AssetsBundle\Assets\Assets;
	use Uneak\AssetsBundle\Pool\AssetInclude;

	interface AssetContainerInterface {
		public function assetInclude(AssetInclude $include, Assets $assets, $parameters, $isVisited);
	}
