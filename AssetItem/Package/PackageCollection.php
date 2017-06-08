<?php

	namespace Uneak\AssetsBundle\AssetItem\Package;

	use Uneak\AssetsBundle\AssetItem\AssetItemCollection;

	use Uneak\AssetsBundle\Exception\NotFoundException;
	use Uneak\AssetsBundle\Exception\PackageNotFoundException;

	class PackageCollection extends AssetItemCollection {

		/**
		 * @param $name
		 *
		 * @return \Uneak\AssetsBundle\AssetItem\Package\Package
		 * @throws \Uneak\AssetsBundle\Exception\PackageNotFoundException
		 */
		public function get($name) {
			try {
				$package = parent::get($name);
			} catch (NotFoundException $e) {
				throw new PackageNotFoundException(sprintf('Package not found %s', $name));
			}

			return $package;
		}

		
	}