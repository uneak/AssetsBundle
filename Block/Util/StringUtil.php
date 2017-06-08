<?php

	namespace Uneak\AssetsBundle\Block\Util;

	class StringUtil {
		/**
		 * This class should not be instantiated.
		 */
		private function __construct() {
		}

		/**
		 * Returns the trimmed data.
		 *
		 * @param string $string
		 *
		 * @return string
		 */
		public static function trim($string) {
			if (null !== $result = @preg_replace('/^[\pZ\p{Cc}]+|[\pZ\p{Cc}]+$/u', '', $string)) {
				return $result;
			}

			return trim($string);
		}

		/**
		 * Converts a fully-qualified class name to a block prefix.
		 *
		 * @param string $fqcn The fully-qualified class name
		 *
		 * @return string|null The block prefix or null if not a valid FQCN
		 */
		public static function fqcnToBlockPrefix($fqcn) {
			// Non-greedy ("+?") to match "type" suffix, if present
			if (preg_match('~([^\\\\]+?)(type)?$~i', $fqcn, $matches)) {
				return strtolower(preg_replace(array('/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'), array('\\1_\\2', '\\1_\\2'), $matches[1]));
			}
		}
	}