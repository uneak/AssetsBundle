<?php

	namespace Uneak\AssetsBundle\AssetItem;


	interface TypedAssetItemInterface extends AssetItemInterface {
		
		/**
		 * @return string
		 */
		public function getType();

		/**
		 * @param string $type
		 */
		public function setType($type);

		/**
		 * @return array
		 */
		public function getDependencies();

		/**
		 * @param array $dependencies
		 */
		public function setDependencies(array $dependencies);
		
	}
