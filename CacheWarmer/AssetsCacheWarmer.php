<?php

	namespace Uneak\AssetsBundle\CacheWarmer;
	          
	use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
	use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
	use Uneak\AssetsBundle\Assets\Assets;


	class AssetsCacheWarmer implements CacheWarmerInterface {
		
		protected $assets;

		public function __construct(Assets $assets) {
			$this->assets = $assets;
		}

		/**
		 * Warms up the cache.
		 *
		 * @param string $cacheDir The cache directory
		 */
		public function warmUp($cacheDir) {
			if ($this->assets instanceof WarmableInterface) {
				$this->assets->warmUp($cacheDir);
			}
		}

		/**
		 * Checks whether this warmer is optional or not.
		 *
		 * @return bool always true
		 */
		public function isOptional() {
			return true;
		}
	}
