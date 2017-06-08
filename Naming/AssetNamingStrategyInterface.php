<?php

	namespace Uneak\AssetsBundle\Naming;

	interface AssetNamingStrategyInterface {

		public function translateName($name);
	}