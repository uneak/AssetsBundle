<?php

	namespace Uneak\AssetsBundle\Assets;

	use Symfony\Component\Config\ConfigCache;
	use Symfony\Component\Config\Resource\FileResource;
	use Symfony\Component\EventDispatcher\EventDispatcherInterface;
	use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
	use Uneak\AssetsBundle\AssetItem\AssetItemInterface;
	use Uneak\AssetsBundle\AssetItem\Bulk\Bulk;
	use Uneak\AssetsBundle\AssetItem\Package\Package;
	use Uneak\AssetsBundle\Finder\FinderExtension;
	use Uneak\AssetsBundle\Finder\FinderExtensionInterface;
	use Uneak\AssetsBundle\Loader\LoaderInterface;

	class Assets implements WarmableInterface {
		/**
		 * @var Bulk
		 */
		private $bulk;
		/**
		 * @var array
		 */
		private $packagesConfig;
		/**
		 * @var array
		 */
		private $prefixConfig;
		/**
		 * @var null
		 */
		private $cacheDir;
		/**
		 * @var bool
		 */
		private $debug;
		/**
		 * @var BulkProcessManager
		 */
		private $bulkProcessManager;
		/**
		 * @var EventDispatcherInterface
		 */
		private $eventDispatcher;
		/**
		 * @var \Uneak\AssetsBundle\Loader\LoaderInterface
		 */
		private $assetLoader;
		/**
		 * @var FinderExtensionInterface
		 */
		private $finder;


		public function __construct(
			EventDispatcherInterface $eventDispatcher,
			LoaderInterface $assetLoader,
			BulkProcessManager $bulkProcessManager,
			array $prefixConfig,
			array $packagesConfig,
			$cacheDir = null,
			$debug = false
		) {
			$this->eventDispatcher = $eventDispatcher;
			$this->assetLoader = $assetLoader;
			$this->bulkProcessManager = $bulkProcessManager;

			$this->packagesConfig = $packagesConfig;
			$this->prefixConfig = $prefixConfig;
			$this->cacheDir = $cacheDir; //null;
			$this->debug = $debug;
		}



		/**
		 * @return Bulk
		 */
		public function getBulk() {
			if (null !== $this->bulk) {
				return $this->bulk;
			}

			if (null === $this->cacheDir) {
				$this->bulk = $this->_getBulk();
			} else {
				$cache = new ConfigCache($this->cacheDir . '/UneakAssets/UneakBulk.php', $this->debug);
				if (!$cache->isFresh()) {
					$this->bulk = $this->_getBulk();
					$cache->write(serialize($this->bulk), $this->bulk->getAllResources());
				} else {
					$this->bulk = unserialize(file_get_contents($cache->getPath()));
				}
			}

			if (null === $this->cacheDir) {
				$this->bulkProcessManager->process($this->bulk);
			} else {
				$cache = new ConfigCache($this->cacheDir . '/UneakAssets/UneakProcessedBulk.php', $this->debug);
				if (!$cache->isFresh()) {
					$this->bulkProcessManager->process($this->bulk);
					$cache->write(serialize($this->bulk), $this->bulk->getAllResources());
				} else {
					$bulk = unserialize(file_get_contents($cache->getPath()));
					$this->bulkProcessManager->check($this->bulk, $bulk);
					$this->bulk = $bulk;
				}
			}
			
			return $this->bulk;
		}

		
		/**
		 * @return Bulk
		 * @throws \Exception
		 * @throws \Symfony\Component\Config\Exception\FileLoaderLoadException
		 */
		private function _getBulk() {
			$configBulk = new Bulk("bulk", $this->prefixConfig);

			foreach ($this->packagesConfig as $packageKey => $packageData) {
				$configBulk->packages()->add(new Package($packageKey, '_bulk', $packageData));

				$subBulk = $this->assetLoader->load($packageData['resource'], 'bulk', array('parent' => '@' . $packageKey, 'tags' => array()));
				$configBulk->merge($subBulk);
			}

			return $configBulk;
		}

		
		public function getFinder() {
			if (null === $this->cacheDir) {
				$this->finder = $this->_getFinder();
			} else {
				$cache = new ConfigCache($this->cacheDir . '/UneakAssets/UneakAssetsFinderExtension.php', $this->debug);
				if (!$cache->isFresh()) {
					$this->finder = $this->_getFinder();
					$cache->write(serialize($this->finder), array(new FileResource($this->cacheDir . '/UneakAssets/UneakProcessedBulk.php')));
				} else {
					$this->finder = unserialize(file_get_contents($cache->getPath()));
				}
			}
			
			return $this->finder;
		}

		private function _getFinder() {
			$bulk = $this->getBulk();
			$symlinks = $bulk->getSymlinks();
			
			array_walk($symlinks, function(AssetItemInterface &$value){
				$value = array("file" => $value->getOutputDir(), "path" => $value->getPath());
			});
			
			$finder = new FinderExtension($symlinks);
			return $finder;
		}
		
		
		public function warmUp($cacheDir) {
			$currentDir = $this->cacheDir;

			// force cache generation
			$this->cacheDir = $cacheDir;
			$this->getFinder();
			$this->cacheDir = $currentDir;
		}

	}