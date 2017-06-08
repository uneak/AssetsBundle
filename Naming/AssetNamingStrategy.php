<?php

	namespace Uneak\AssetsBundle\Naming;

	class AssetNamingStrategy implements AssetNamingStrategyInterface {

		public function translateName($name) {
			return str_replace(array('-', '.', DIRECTORY_SEPARATOR), '_', $name);
		}
	}