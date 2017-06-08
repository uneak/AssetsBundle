<?php

	namespace Uneak\AssetsBundle\Block;

	/**
	 * The central registry of the Block component.
	 *
	 */
	interface BlockRegistryInterface {
		/**
		 * Returns a block type by name.
		 *
		 * This methods registers the type extensions from the block extensions.
		 *
		 * @param string $name The name of the type
		 *
		 * @return ResolvedBlockTypeInterface The type
		 *
		 * @throws Exception\InvalidArgumentException if the type can not be retrieved from any extension
		 */
		public function getType($name);

		/**
		 * Returns whether the given block type is supported.
		 *
		 * @param string $name The name of the type
		 *
		 * @return bool Whether the type is supported
		 */
		public function hasType($name);

		/**
		 * Returns the extensions loaded by the framework.
		 *
		 * @return BlockExtensionInterface[]
		 */
		public function getExtensions();
	}
