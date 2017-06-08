<?php

	namespace Uneak\AssetsBundle\Bower;

	use Symfony\Component\Config\Resource\FileResource;
	use Symfony\Component\Filesystem\Filesystem;
	use Uneak\AssetsBundle\AssetItem\Library\BowerLibrary;
	use Uneak\AssetsBundle\AssetItem\Library\Library;
	use Uneak\AssetsBundle\AssetItem\Package\Package;
	use Uneak\AssetsBundle\Exception\InvalidMappingException;
	use Uneak\AssetsBundle\Naming\AssetNamingStrategyInterface;

	class BowerPackage {
		/**
		 * @var Package
		 */
		private $package;
		/**
		 * @var Library[]
		 */
		private $libraries = array();
		/**
		 * @var array
		 */
		private $mapping;
		/**
		 * @var string
		 */
		private $hash;
		/**
		 * @var string
		 */
		private $outputDir;
		/**
		 * @var \Symfony\Component\Filesystem\Filesystem
		 */
		private $fs;
		/**
		 * @var \Uneak\AssetsBundle\Naming\AssetNamingStrategyInterface
		 */
		private $assetNamingStrategy;

		public function __construct(Package $package, $outputDir, AssetNamingStrategyInterface $assetNamingStrategy, Filesystem $fs) {
			$this->package = $package;
			$this->outputDir = $outputDir;
			$this->fs = $fs;
			$this->assetNamingStrategy = $assetNamingStrategy;
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
			$this->libraries[$library->getEndpoint("name")] = $library;
			$this->hash = null;

			return $this;
		}

		public function removeLibrary($name) {
			unset($this->libraries[$name]);
			$this->hash = null;

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

		public function check($hash) {
			return $hash == $this->getHash();
		}

		public function getHash() {
			if (!$this->hash) {

				$resolutions = array();
				$parameters = $this->getPackage()->getParameters();
				if (isset($parameters['bower']['resolutions'])) {
					foreach ($parameters['bower']['resolutions'] as $name => $resolution) {
						$resolutions[] = $name . '#' . $resolution;
					}
					sort($resolutions);
				}

				$libraries = array();
				/** @var $library BowerLibrary */
				foreach ($this->getLibraries() as $library) {
					if ($endpoint = $library->getEndpoint()) {
						$libraries[] = join('#', $endpoint);
					}
				}
				sort($libraries);

				$this->hash = hash('md5', serialize(array(
					$this->getOutputDir(),
					$resolutions,
					$libraries,
				)));
			}

			return $this->hash;
		}


		public function getPackageBower() {
			$bower = array();
			$bower['name'] = $this->getPackage()->getName();
			$bower['dependencies'] = array();
			$bower['resolutions'] = array();

			$parameters = $this->getPackage()->getParameters();

			if (isset($parameters['bower']['resolutions'])) {
				$bower['resolutions'] = $parameters['bower']['resolutions'];
			}

			/** @var $library BowerLibrary */
			foreach ($this->getLibraries() as $library) {
				$bower['dependencies'][$library->getEndpoint('name')] = $library->getEndpoint('source') . '#' . $library->getEndpoint('target');
			}
			$bower = array_filter($bower, function ($value) {
				return $value !== null;
			});

			return json_encode($bower, JSON_FORCE_OBJECT);
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
		 * @return array
		 */
		public function getMapping() {
			if (!$this->mapping) {
				$mappingPath = join(DIRECTORY_SEPARATOR, array($this->getOutputDir(), '.mapping'));
				if (!$this->fs->exists($mappingPath)) {
					// TODO: ecrire le message
					throw new InvalidMappingException("Les assets ont été modifié, vous devez recompiler");
				}
				$packageMap = unserialize(file_get_contents($mappingPath));
				if ($packageMap['hash'] != $this->getHash()) {
					// TODO: ecrire le message
					throw new InvalidMappingException("Les assets ont été modifié, vous devez recompiler");
				}
				$dir = $packageMap['canonicalDir'];
				$this->buildLibrariesMap($packageMap, $this->mapping, $dir);
			}

			return $this->mapping;
		}


		private function buildLibrariesMap($mapping, &$librariesMap, $outputDir) {
			foreach ($mapping['dependencies'] as $libraryMapKey => $libraryMapData) {

				if (isset($libraryMapData['pkgMeta']['_direct']) && $libraryMapData['pkgMeta']['_direct']) {
					continue;
				}

				$dir = rtrim($this->fs->makePathRelative($libraryMapData['canonicalDir'], $outputDir), DIRECTORY_SEPARATOR);

				$main = array();
				foreach ((array)$libraryMapData['pkgMeta']['main'] as $filename) {
					$name = $this->assetNamingStrategy->translateName($filename);
					$main[] = array(
						'name' => $name,
						'file' => $filename
					);
				}

				$libraryMap = array(
					'type'         => 'bower',
					'path'         => $dir,
					'input_dir'    => $dir,
					'output_dir'   => $dir,
					'endpoint'     => $libraryMapData['endpoint'],
					'parameters'   => array(
						'version' => isset($libraryMapData['pkgMeta']['version']) ? $libraryMapData['pkgMeta']['version'] : null,
					),
					'main'         => $main,
					'dependencies' => array(),
				);


				foreach ($libraryMapData['dependencies'] as $dependencyId => $dependency) {
					$libraryMap['dependencies'][] = $dependencyId;
					$this->buildLibrariesMap($libraryMapData, $librariesMap, $outputDir);
				}
				$librariesMap[$libraryMapKey] = $libraryMap;
			}
		}

	}