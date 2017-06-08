<?php

	namespace Uneak\AssetsBundle\AssetItem;

	use Uneak\AssetsBundle\Assets\Assets;
	use Uneak\AssetsBundle\Pool\AssetInclude;

	class AssetContainer implements AssetContainerInterface {

		public function assetInclude(AssetInclude $include, Assets $assets, $parameters, $isVisited) {
			if ($isVisited) {
				return;
			}
			$include
//				->set('DOUCE_datatable_js', $generator->find('@global:datatable:datatable_js'))
				->set('@global:datatable:datatable_js', array('name' => $parameters[0]))
			;
		}

	}