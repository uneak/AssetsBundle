<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;


	trait WatchTrait {

		private $watch;

		/**
		 * @return null
		 */
		public function getWatch() {
			return $this->watch;
		}

		/**
		 * @param null $watch
		 *
		 * @return $this
		 */
		public function setWatch($watch) {
			$this->watch = $watch;

			return $this;
		}

	}
