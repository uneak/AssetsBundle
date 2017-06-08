<?php

	namespace Uneak\AssetsBundle\CacheWarmer;
	          
	use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
	use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
	use Uneak\AssetsBundle\Npm\Npm;


	class NpmCacheWarmer implements CacheWarmerInterface {
		
		protected $npm;

		public function __construct(Npm $npm) {
			$this->npm = $npm;
		}

		/**
		 * Warms up the cache.
		 *
		 * @param string $cacheDir The cache directory
		 */
		public function warmUp($cacheDir) {
			if ($this->npm instanceof WarmableInterface) {
				$this->npm->warmUp($cacheDir);
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
