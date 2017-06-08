<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;


	trait LoaderTrait {
		/**
		 * @var array
		 */
		private $loaders = array();

		public function addLoader($loader) {
			if (!$this->hasLoader($loader)) {
				$this->loaders[] = $loader;
			}

			return $this;
		}

		public function removeLoader($loader) {
			if ($i = array_search($loader, $this->loaders) !== false) {
				unset($this->loaders[$i]);
			}

			return $this;
		}

		public function hasLoader($loader) {
			return (array_search($loader, $this->loaders) !== false);
		}

		/**
		 * @return array
		 */
		public function getLoaders() {
			return $this->loaders;
		}

		/**
		 * @param array $loaders
		 *
		 * @return $this
		 */
		public function setLoaders(array $loaders) {
			foreach ($loaders as $loader) {
				$this->addLoader($loader);
			}

			return $this;
		}

		
	}
