<?php

	namespace Uneak\AssetsBundle\Block;

	/**
	 * Interface for extensions which provide types, type extensions and a guesser.
	 */
	interface BlockExtensionInterface {
		/**
		 * Returns a type by name.
		 *
		 * @param string $name The name of the type
		 *
		 * @return BlockTypeInterface The type
		 *
		 * @throws Exception\InvalidArgumentException if the given type is not supported by this extension
		 */
		public function getType($name);

		/**
		 * Returns whether the given type is supported.
		 *
		 * @param string $name The name of the type
		 *
		 * @return bool Whether the type is supported by this extension
		 */
		public function hasType($name);

		/**
		 * Returns the extensions for the given type.
		 *
		 * @param string $name The name of the type
		 *
		 * @return BlockTypeExtensionInterface[] An array of extensions as BlockTypeExtensionInterface instances
		 */
		public function getTypeExtensions($name);

		/**
		 * Returns whether this extension provides type extensions for the given type.
		 *
		 * @param string $name The name of the type
		 *
		 * @return bool Whether the given type has extensions
		 */
		public function hasTypeExtensions($name);

	}
