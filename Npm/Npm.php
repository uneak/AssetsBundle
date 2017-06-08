<?php

	namespace Uneak\AssetsBundle\Npm;

	use Symfony\Component\Config\ConfigCache;
	use Symfony\Component\Config\Resource\FileResource;
	use Symfony\Component\EventDispatcher\EventDispatcherInterface;
	use Symfony\Component\Filesystem\Filesystem;
	use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
	use Symfony\Component\HttpKernel\KernelInterface;
	use Uneak\AssetsBundle\Finder\FinderExtension;
	use Uneak\AssetsBundle\Finder\FinderExtensionInterface;

	class Npm implements WarmableInterface {
		/**
		 * @var FinderExtensionInterface
		 */
		private $finder;
		/**
		 * @var NpmPackagesInterface
		 */
		private $packages = null;
		/**
		 * @var string
		 */
		private $cacheDir;
		/**
		 * @var bool
		 */
		private $debug;
		/**
		 * @var Filesystem
		 */
		private $fileSystem;
		/**
		 * @var KernelInterface
		 */
		private $kernel;
		/**
		 * @var EventDispatcherInterface
		 */
		private $eventDispatcher;


		public function __construct(KernelInterface $kernel, Filesystem $fileSystem, EventDispatcherInterface $eventDispatcher, $cacheDir, $debug) {
			$this->cacheDir = $cacheDir;
			$this->debug = $debug;
			$this->kernel = $kernel;
			$this->fileSystem = $fileSystem;
			$this->eventDispatcher = $eventDispatcher;
		}


		/**
		 * @return NpmPackagesInterface
		 * @throws \Exception
		 */
		public function getPackages() {
			if (null !== $this->packages) {
				return $this->packages;
			}

			if (null === $this->cacheDir) {
				$this->packages = $this->_getPackages();

			} else {
				$cache = new ConfigCache($this->cacheDir . '/UneakAssets/UneakNpmPackages.php', $this->debug);
				if (!$cache->isFresh()) {
					$this->packages = $this->_getPackages();
					$cache->write(serialize($this->packages), $this->packages->getResources());

				} else {
					$this->packages = unserialize(file_get_contents($cache->getPath()));

				}

			}

			return $this->packages;
		}


		/**
		 * @return NpmPackagesInterface
		 * @throws \Exception
		 */
		private function _getPackages() {
			$packages = new NpmPackages();
			$bundles = $this->kernel->getBundles();
			foreach ($bundles as $bundle) {
				$packageJson = $bundle->getPath() . "/package.json";
				if ($this->fileSystem->exists($packageJson)) {
					$packages->addPackage($bundle->getName(), new NpmPackage($bundle->getPath()));
					$packages->addResource(new FileResource($packageJson));
				}
			}
			
			return $packages;
		}

		/**
		 * Warms up the cache.
		 *
		 * @param string $cacheDir The cache directory
		 */
		public function warmUp($cacheDir) {
			$currentDir = $this->cacheDir;
			// force cache generation
			$this->cacheDir = $cacheDir;
			$this->getFinder();
			$this->cacheDir = $currentDir;
		}



		public function getFinder() {
			if (null === $this->cacheDir) {
				$this->finder = $this->_getFinder();
			} else {
				$cache = new ConfigCache($this->cacheDir . '/UneakAssets/UneakNpmFinderExtension.php', $this->debug);
				if (!$cache->isFresh()) {
					$this->finder = $this->_getFinder();
					$cache->write(serialize($this->finder), array(new FileResource($this->cacheDir . '/UneakAssets/UneakNpmPackages.php')));
				} else {
					$this->finder = unserialize(file_get_contents($cache->getPath()));
				}
			}

			return $this->finder;
		}

		private function _getFinder() {
			$packages = $this->getPackages();
			$symlinks = array();
			foreach ($packages->getPackages() as $name => $package) {
				$symlinks["@$name:module"] = array(
					'path' => $package->getModulesPath()
				);
			}

			$finder = new FinderExtension($symlinks);
			return $finder;
		}

	}
