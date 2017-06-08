<?php

	namespace Uneak\AssetsBundle\Bower;

	use Symfony\Component\Filesystem\Filesystem;
	use Uneak\AssetsBundle\AssetItem\Bulk\BulkInterface;
	use Uneak\AssetsBundle\AssetItem\Library\Library;
	use Uneak\AssetsBundle\Naming\AssetNamingStrategyInterface;

	class BowerBulk {
		/**
		 * @var BowerPackage[]
		 */
		protected $bowerPackages = array();
		/**
		 * @var string
		 */
		private $hash;
		/**
		 * @var \Uneak\AssetsBundle\AssetItem\Bulk\BulkInterface
		 */
		private $bulk;
		/**
		 * @var \Uneak\AssetsBundle\Naming\AssetNamingStrategyInterface
		 */
		private $assetNamingStrategy;
		/**
		 * @var \Symfony\Component\Filesystem\Filesystem
		 */
		private $fs;
		

		public function __construct(BulkInterface $bulk, AssetNamingStrategyInterface $assetNamingStrategy, Filesystem $fs) {
			$this->bulk = $bulk;
			$this->assetNamingStrategy = $assetNamingStrategy;
			$this->fs = $fs;

			/** @var $library Library */
			foreach ($this->bulk->libraries()->findByType("bower") as $library) {
				if (!$this->hasBowerPackage($library->getParent())) {
					$bowerPackage = new BowerPackage(
						$this->bulk->packages()->get(ltrim($library->getParent(), "@")),
						$this->bulk->getOutputDir(),
						$this->assetNamingStrategy,
						$this->fs
					);
					$this->addBowerPackage($library->getParent(), $bowerPackage);
				} else {
					$bowerPackage = $this->getBowerPackage($library->getParent());
				}

				$bowerPackage->addLibrary($library);
			}
		}


		public function getHash() {
			if (!$this->hash) {

				$packages = array();
				foreach ($this->getBowerPackages() as $name => $bowerPackage) {
					$packages[] = $name . "#" . $bowerPackage->getHash();
				}
				sort($packages);
				
				$parameters = $this->bulk->getParameters();
				$parameters = (isset($parameters['bower'])) ? $parameters['bower'] : null;
				
				$this->hash = hash('md5', serialize(array(
					$this->bulk->getOutputDir(),
					$this->bulk->getInputDir(),
					$this->bulk->getPath(),
					$parameters,
					$packages,
				)));
			}

			return $this->hash;
		}
		
		

		/**
		 * @return BowerPackage[]
		 */
		public function getBowerPackages() {
			return $this->bowerPackages;
		}

		/**
		 * @param string $name
		 * @param BowerPackage $bowerPackage
		 *
		 * @return $this
		 */
		public function addBowerPackage($name, BowerPackage $bowerPackage) {
			if (!isset($this->bowerPackages[$name])) {
				$this->bowerPackages[$name] = $bowerPackage;
			}
			return $this;
		}

		/**
		 * @param string $name
		 *
		 * @return $this
		 */
		public function removeBowerPackage($name) {
			unset($this->bowerPackages[$name]);
			return $this;
		}

		/**
		 * @param string $name
		 *
		 * @return boolean
		 */
		public function hasBowerPackage($name) {
			return isset($this->bowerPackages[$name]);
		}

		/**
		 * @param string $name
		 *
		 * @return BowerPackage
		 */
		public function getBowerPackage($name) {
			return $this->bowerPackages[$name];
		}

		public function getMapping() {
			$mappings = array();
			foreach ($this->getBowerPackages() as $bowerPackage) {
				$mappings['@' . $bowerPackage->getPackage()->getName()] = $bowerPackage->getMapping();
			}
			return $mappings;
		}
	}