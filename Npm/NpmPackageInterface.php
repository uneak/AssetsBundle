<?php

	namespace Uneak\AssetsBundle\Npm;


	interface NpmPackageInterface extends \Serializable {
		/**
		 * @return string
		 */
		public function getPath();
		/**
		 * @return string
		 */
		public function getModulesPath();
		/**
		 * @return array
		 */
		public function getNodeModules();
		/**
		 * @param $name
		 *
		 * @return bool
		 */
		public function hasNodeModule($name);

	}
