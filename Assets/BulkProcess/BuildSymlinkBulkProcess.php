<?php

	namespace Uneak\AssetsBundle\Assets\BulkProcess;


	use Uneak\AssetsBundle\AssetItem\Asset\Asset;
	use Uneak\AssetsBundle\AssetItem\Bulk\BulkInterface;
	use Uneak\AssetsBundle\AssetItem\Library\Library;
	use Uneak\AssetsBundle\AssetItem\Package\Package;
	use Uneak\AssetsBundle\Assets\AbstractBulkProcess;

	class BuildSymlinkBulkProcess extends AbstractBulkProcess {

		public function process(BulkInterface $bulk) {
			/** @var $package Package */
			foreach ($bulk->packages()->all() as $package) {
				$bulk->addSymlink($package->getId(), $package);
			}

			/** @var $library Library */
			foreach ($bulk->libraries()->all() as $library) {
				$symlinks = array();
				$symlinks[] = $library->getId();
				foreach ($library->getTags() as $tag) {
					$symlinks[] = '#' . $tag . ':' . $library->getName();
				}
				$bulk->addSymlink($symlinks, $library);
			}

			/** @var $asset Asset */
			foreach ($bulk->assets()->all() as $asset) {
				$symlinks = array();
				$symlinks[] = $asset->getId();
				foreach ($asset->getTags() as $tag) {
					$symlinks[] = preg_replace("/^(@.*?)(?::|$)/", "#" . $tag . ":", $asset->getParent(), 1) . ':' . $asset->getName();
					$symlinks[] = '#' . $tag . ':' . $asset->getName();
				}
				$bulk->addSymlink($symlinks, $asset);
			}
		}

	}