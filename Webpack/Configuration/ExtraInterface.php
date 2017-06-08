<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	interface ExtraInterface {
		/**
		 * @return array
		 */
		public function getExtras();
		/**
		 * @param array $extras
		 *
		 * @return $this
		 */
		public function setExtras(array $extras);
		/**
		 * @param string $key
		 * @param string $extra
		 *
		 * @return $this
		 */
		public function addExtra($key, $extra);
		public function removeExtra($key);
		public function hasExtra($key);
	}
