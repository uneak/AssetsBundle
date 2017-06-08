<?php

	namespace Uneak\AssetsBundle\Assets\BulkProcess;


	use Uneak\AssetsBundle\AssetItem\Asset\Asset;
	use Uneak\AssetsBundle\AssetItem\AssetItemInterface;
	use Uneak\AssetsBundle\AssetItem\Bulk\BulkInterface;
	use Uneak\AssetsBundle\AssetItem\Library\Library;
	use Uneak\AssetsBundle\Assets\BulkProcessInterface;
	use Uneak\AssetsBundle\Exception\NotFoundException;
	use Uneak\AssetsBundle\Finder\BulkFinder;

	class ResolveSymlinkBulkProcess implements BulkProcessInterface {

		public function process(BulkInterface $bulk) {
			/** @var $library Library */
			foreach ($bulk->libraries()->all() as $library) {
				$library->setParent($this->resolveSymlink($bulk, $library->getParent(), $library));
				$library->setDependencies($this->resolveSymlink($bulk, $library->getDependencies(), $library));
				$library->setMain($this->resolveSymlink($bulk, $library->getMain(), $library));
			}

			/** @var $asset Asset */
			foreach ($bulk->assets()->all() as $asset) {
				$asset->setParent($this->resolveSymlink($bulk, $asset->getParent(), $asset));
				$asset->setDependencies($this->resolveSymlink($bulk, $asset->getDependencies(), $asset));
			}
		}


		private function resolveSymlink(BulkInterface $bulk, $symlink, AssetItemInterface $resolveItem = null) {
			if (is_array($symlink)) {
				array_walk($symlink, function (&$name, $key) use ($bulk, $resolveItem) {
					try {
						$object = $bulk->find($name, $resolveItem);
						$name = $object->getId();
					} catch (NotFoundException $e) {
					}
				});
				$symlink = array_unique($symlink);
			} else {
				try {
					$object = $bulk->find($symlink, $resolveItem);
					$symlink = $object->getId();
				} catch (NotFoundException $e) {
				}
			}

			return $symlink;
		}


		public function check(BulkInterface $bulk, BulkInterface $processedBulk) { }
		
	}