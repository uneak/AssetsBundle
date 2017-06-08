<?php

	namespace Uneak\AssetsBundle\AssetItem\Library;

	use Uneak\AssetsBundle\AssetItem\AssetItemCollection;
	use Uneak\AssetsBundle\Exception\LibraryNotFoundException;
	use Uneak\AssetsBundle\Exception\NotFoundException;

	class LibraryCollection extends AssetItemCollection {

		/**
		 * @param $name
		 *
		 * @return \Uneak\AssetsBundle\AssetItem\Library\Library
		 * @throws \Uneak\AssetsBundle\Exception\LibraryNotFoundException
		 */
		public function get($name) {
			try {
				$library = parent::get($name);
			} catch (NotFoundException $e) {
				throw new LibraryNotFoundException(sprintf('Library not found %s', $name));
			}

			return $library;
		}

		
	}