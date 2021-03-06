<?php

	namespace Uneak\AssetsBundle\Naming;

	class AssetNamingStrategy implements AssetNamingStrategyInterface {

		public function translateName($name) {
			$slug = str_replace(array('-', DIRECTORY_SEPARATOR), '_', $name);
			$slug = str_replace(array('.'), '__', $slug);
			return $slug;
		}
	}