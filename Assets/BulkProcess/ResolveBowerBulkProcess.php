<?php

	namespace Uneak\AssetsBundle\Assets\BulkProcess;

	use Symfony\Component\Filesystem\Filesystem;
	use Uneak\AssetsBundle\AssetItem\Asset\Asset;
	use Uneak\AssetsBundle\AssetItem\Bulk\BulkInterface;
	use Uneak\AssetsBundle\AssetItem\Library\BowerLibrary;
	use Uneak\AssetsBundle\Assets\AbstractBulkProcess;
	use Uneak\AssetsBundle\Bower\BowerBulk;
	use Uneak\AssetsBundle\Naming\AssetNamingStrategyInterface;
	use Uneak\AssetsBundle\Process\Bower\BowerProcess;

	class ResolveBowerBulkProcess extends AbstractBulkProcess {
		/**
		 * @var \Symfony\Component\Filesystem\Filesystem
		 */
		private $fs;
		/**
		 * @var BowerProcess
		 */
		private $bower;
		/**
		 * @var \Uneak\AssetsBundle\Naming\AssetNamingStrategyInterface
		 */
		private $assetNamingStrategy;

		public function __construct(Filesystem $fs, BowerProcess $bower, AssetNamingStrategyInterface $assetNamingStrategy) {
			$this->fs = $fs;
			$this->bower = $bower;
			$this->assetNamingStrategy = $assetNamingStrategy;
		}

		public function process(BulkInterface $bulk) {
			$bowerBulk = new BowerBulk($bulk, $this->assetNamingStrategy, $this->fs);
			$packageMappings = $bowerBulk->getMapping();

			foreach ($packageMappings as $parent => $packageMapping) {
				if ($bowerBulk->hasBowerPackage($parent) && $packageMapping) {
					$bowerPackage = $bowerBulk->getBowerPackage($parent);

					foreach ($packageMapping as $name => $mapping) {

						// if libray n'exist pas
						if (!$bowerPackage->hasLibrary($name)) {
							$library = new BowerLibrary($name, $parent);
							$bulk->libraries()->add($library);
						} else {
							$library = $bowerPackage->getLibrary($name);
						}

						// GESTION DES DEPENDENCIES
						$mappingDependencies = $mapping['dependencies'];
						unset($mapping['dependencies']);

						$dependencies = $library->getDependencies();
						foreach ($mappingDependencies as $dependency) {
							$dependencyName = $parent . ':' . $dependency;
							if (!in_array($dependencyName, $dependencies)) {
								$dependencies[] = $dependencyName;
							}
						}
						$library->setDependencies($dependencies);


						// GESTION DES MAIN SCRIPT
						$mappingMains = $mapping['main'];
						unset($mapping['main']);

						foreach ($mappingMains as $main) {
							$bulk->assets()->add(new Asset(
								$main['name'],
								$parent . ':' . $library->getName(),
								array('file' => $main['file'])
							));
						}

						// add main if not exist
						if (!count($library->getMain())) {
							$mains = array();
							foreach ($mappingMains as $main) {
								$mainName = $parent . ':' . $library->getName() . ':' . $main['name'];
								if (!in_array($mainName, $mains)) {
									$mains[] = $mainName;
								}
							}
							$library->setMain($mains);
						}

						$library->merge($mapping);

					}
				}
			}
		}


		public function check(BulkInterface $bulk, BulkInterface $processedBulk) {
			$bowerBulk = new BowerBulk($bulk, $this->assetNamingStrategy, $this->fs);
			$bowerProcessedBulk = new BowerBulk($processedBulk, $this->assetNamingStrategy, $this->fs);
			return $bowerBulk->getHash() == $bowerProcessedBulk->getHash();
		}
	}