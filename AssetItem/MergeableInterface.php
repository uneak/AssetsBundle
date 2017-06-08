<?php

	namespace Uneak\AssetsBundle\AssetItem;
	
	interface MergeableInterface {
		/**
		 * @param $mixed
		 *
		 * @return array
		 */
		public function merge($mixed);

	}
