<?php

	namespace Uneak\AssetsBundle\Install;

	use Uneak\AssetsBundle\AssetItem\Bulk\BulkInterface;
	use Uneak\AssetsBundle\AssetItem\Library\Library;

	class InstallBulk {
		/**
		 * @var InstallPackage[]
		 */
		protected $installPackages = array();
		/**
		 * @var \Uneak\AssetsBundle\AssetItem\Bulk\BulkInterface
		 */
		private $bulk;



		public function __construct(BulkInterface $bulk) {
			$this->bulk = $bulk;

			/** @var $library Library */
			foreach ($this->bulk->libraries()->findByType("install") as $library) {
				if (!$this->hasInstallPackage($library->getParent())) {
					$installPackage = new InstallPackage(
						$this->bulk->packages()->get(ltrim($library->getParent(), "@")),
						$this->bulk->getInputDir(),
						$this->bulk->getOutputDir()
					);
					$this->addInstallPackage($library->getParent(), $installPackage);
				} else {
					$installPackage = $this->getInstallPackage($library->getParent());
				}

				$installPackage->addLibrary($library);
			}
		}

		/**
		 * @return InstallPackage[]
		 */
		public function getInstallPackages() {
			return $this->installPackages;
		}

		/**
		 * @param string $name
		 * @param InstallPackage $bowerPackage
		 *
		 * @return $this
		 */
		public function addInstallPackage($name, InstallPackage $bowerPackage) {
			if (!isset($this->installPackages[$name])) {
				$this->installPackages[$name] = $bowerPackage;
			}
			return $this;
		}

		public function removeInstallPackage($name) {
			unset($this->installPackages[$name]);
			return $this;
		}

		public function hasInstallPackage($name) {
			return isset($this->installPackages[$name]);
		}

		public function getInstallPackage($name) {
			return $this->installPackages[$name];
		}


	}