<?php

	namespace Uneak\AssetsBundle\Block\Util;

	class BlockUtil {
		/**
		 * This class should not be instantiated.
		 */
		private function __construct() {
		}

		/**
		 * Returns whether the given data is empty.
		 *
		 * This logic is reused multiple times throughout the processing of
		 * a block and needs to be consistent. PHP's keyword `empty` cannot
		 * be used as it also considers 0 and "0" to be empty.
		 *
		 * @param mixed $data
		 *
		 * @return bool
		 */
		public static function isEmpty($data) {
			// Should not do a check for array() === $data!!!
			// This method is used in occurrences where arrays are
			// not considered to be empty, ever.
			return null === $data || '' === $data;
		}
	}
