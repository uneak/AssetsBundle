<?php

	namespace Uneak\AssetsBundle\Assets\BulkProcess;


	use Uneak\AssetsBundle\AssetItem\Asset\Asset;
	use Uneak\AssetsBundle\AssetItem\AssetItemInterface;
	use Uneak\AssetsBundle\AssetItem\Bulk\BulkInterface;
	use Uneak\AssetsBundle\Assets\AbstractBulkProcess;

	class BuildAssetsUrlBulkProcess extends AbstractBulkProcess {

		public function process(BulkInterface $bulk) {
			foreach ($bulk->packages()->all() as $item) {
				list($inputDir, $outputDir, $path) = $this->getPaths($bulk, $item);
				$item->setInputDir(join(DIRECTORY_SEPARATOR,
						array_filter(array($bulk->getInputDir(), $inputDir), function ($value) {
							return $value !== null && $value !== "";
						}))
				);
				$item->setOutputDir(join(DIRECTORY_SEPARATOR,
						array_filter(array($bulk->getOutputDir(), $outputDir), function ($value) {
							return $value !== null && $value !== "";
						}))
				);
				$item->setPath(join(DIRECTORY_SEPARATOR,
						array_filter(array($bulk->getPath(), $path), function ($value) {
							return $value !== null && $value !== "";
						}))
				);
			}
			foreach ($bulk->libraries()->all() as $item) {
				list($inputDir, $outputDir, $path) = $this->getPaths($bulk, $item);
				$item->setInputDir($inputDir);
				$item->setOutputDir($outputDir);
				$item->setPath($path);
			}

			/** @var $item Asset */
			foreach ($bulk->assets()->all() as $item) {
				list($inputDir, $outputDir, $path) = $this->getPaths($bulk, $item);
				$item->setInputDir(join(DIRECTORY_SEPARATOR,
						array_filter(array($inputDir, $item->getFile()), function ($value) {
							return $value !== null && $value !== "";
						}))
				);
				$item->setOutputDir(join(DIRECTORY_SEPARATOR,
						array_filter(array($outputDir, $item->getFile()), function ($value) {
							return $value !== null && $value !== "";
						}))
				);
				$item->setPath(join(DIRECTORY_SEPARATOR,
						array_filter(array($path, $item->getFile()), function ($value) {
							return $value !== null && $value !== "";
						}))
				);

			}

		}


		private function getPaths(BulkInterface $bulk, AssetItemInterface $assetItem, $prefix = null) {
			$inputPaths = array($assetItem->getInputDir());
			$outputPaths = array($assetItem->getOutputDir());
			$paths = array($assetItem->getPath());

			$parent = $assetItem->getParent();
			if ($parent != '_bulk') {
				$parentItem = $bulk->find($assetItem->getParent());

				array_unshift($inputPaths, $parentItem->getInputDir());
				array_unshift($outputPaths, $parentItem->getOutputDir());
				array_unshift($paths, $parentItem->getPath());
			}

			return array(
				join(DIRECTORY_SEPARATOR, array_filter($inputPaths, function ($value) {
					return $value !== null && $value !== "";
				})),
				join(DIRECTORY_SEPARATOR, array_filter($outputPaths, function ($value) {
					return $value !== null && $value !== "";
				})),
				join(DIRECTORY_SEPARATOR, array_filter($paths, function ($value) {
					return $value !== null && $value !== "";
				})),
			);
		}


	}