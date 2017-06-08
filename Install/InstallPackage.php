<?php

	namespace Uneak\AssetsBundle\Install;

	use Uneak\AssetsBundle\AssetItem\Library\Library;
	use Uneak\AssetsBundle\AssetItem\Package\Package;

	class InstallPackage {
		/**
		 * @var Package
		 */
		private $package;
		/**
		 * @var Library[]
		 */
		private $libraries = array();
		/**
		 * @var string
		 */
		private $outputDir;
		/**
		 * @var string
		 */
		private $inputDir;



		public function __construct(Package $package, $inputDir, $outputDir) {
			$this->package = $package;
			$this->inputDir = $inputDir;
			$this->outputDir = $outputDir;
		}


		/**
		 * @return Library[]
		 */
		public function getLibraries() {
			return $this->libraries;
		}

		/**
		 * @param Library[] $libraries
		 *
		 * @return $this
		 */
		public function setLibraries(array $libraries) {
			foreach ($libraries as $library) {
				$this->addLibrary($library);
			}

			return $this;
		}

		/**
		 * @param mixed $library
		 *
		 * @return $this
		 */
		public function addLibrary(Library $library) {
			$this->libraries[$library->getName()] = $library;

			return $this;
		}

		public function removeLibrary($name) {
			unset($this->libraries[$name]);

			return $this;
		}

		public function getLibrary($name) {
			return $this->libraries[$name];
		}

		public function hasLibrary($name) {
			return isset($this->libraries[$name]);
		}

		/**
		 * @return \Uneak\AssetsBundle\AssetItem\Package\Package
		 */
		public function getPackage() {
			return $this->package;
		}

		/**
		 * @return string
		 */
		public function getOutputDir() {
			return join(DIRECTORY_SEPARATOR,
				array_filter(array(rtrim($this->outputDir, DIRECTORY_SEPARATOR), trim($this->getPackage()->getOutputDir(), DIRECTORY_SEPARATOR)), function ($value) {
					return $value !== null && $value !== "";
				}));
		}

		/**
		 * @return string
		 */
		public function getInputDir() {
			
			return join(DIRECTORY_SEPARATOR,
				array_filter(array(rtrim($this->inputDir, DIRECTORY_SEPARATOR), trim($this->getPackage()->getInputDir(), DIRECTORY_SEPARATOR)), function ($value) {
					return $value !== null && $value !== "";
				}));
		}
		
	}