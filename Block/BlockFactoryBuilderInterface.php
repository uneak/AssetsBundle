<?php

	namespace Uneak\AssetsBundle\Block;

	/**
	 * A builder for BlockFactoryInterface objects.
	 *
	 */
	interface BlockFactoryBuilderInterface {
		/**
		 * Sets the factory for creating ResolvedBlockTypeInterface instances.
		 *
		 * @param ResolvedBlockTypeFactoryInterface $resolvedTypeFactory
		 *
		 * @return BlockFactoryBuilderInterface The builder
		 */
		public function setResolvedTypeFactory(ResolvedBlockTypeFactoryInterface $resolvedTypeFactory);

		/**
		 * Adds an extension to be loaded by the factory.
		 *
		 * @param BlockExtensionInterface $extension The extension
		 *
		 * @return BlockFactoryBuilderInterface The builder
		 */
		public function addExtension(BlockExtensionInterface $extension);

		/**
		 * Adds a list of extensions to be loaded by the factory.
		 *
		 * @param BlockExtensionInterface[] $extensions The extensions
		 *
		 * @return BlockFactoryBuilderInterface The builder
		 */
		public function addExtensions(array $extensions);

		/**
		 * Adds a block type to the factory.
		 *
		 * @param BlockTypeInterface $type The block type
		 *
		 * @return BlockFactoryBuilderInterface The builder
		 */
		public function addType(BlockTypeInterface $type);

		/**
		 * Adds a list of block types to the factory.
		 *
		 * @param BlockTypeInterface[] $types The block types
		 *
		 * @return BlockFactoryBuilderInterface The builder
		 */
		public function addTypes(array $types);

		/**
		 * Adds a block type extension to the factory.
		 *
		 * @param BlockTypeExtensionInterface $typeExtension The block type extension
		 *
		 * @return BlockFactoryBuilderInterface The builder
		 */
		public function addTypeExtension(BlockTypeExtensionInterface $typeExtension);

		/**
		 * Adds a list of block type extensions to the factory.
		 *
		 * @param BlockTypeExtensionInterface[] $typeExtensions The block type extensions
		 *
		 * @return BlockFactoryBuilderInterface The builder
		 */
		public function addTypeExtensions(array $typeExtensions);

		/**
		 * Builds and returns the factory.
		 *
		 * @return BlockFactoryInterface The block factory
		 */
		public function getBlockFactory();
	}
