<?php

	namespace Uneak\AssetsBundle\Block;

	interface BlockBuilderInterface extends \Traversable, \Countable, BlockConfigBuilderInterface {
		/**
		 * Adds a new field to this group. A field must have a unique name within
		 * the group. Otherwise the existing field is overwritten.
		 *
		 * If you add a nested group, this group should also be represented in the
		 * object hierarchy.
		 *
		 * @param string|int|BlockBuilderInterface $child
		 * @param string|null                     $type
		 * @param array                           $options
		 *
		 * @return BlockBuilderInterface The builder object
		 */
		public function add($child, $type = null, array $options = array());

		/**
		 * Creates a block builder.
		 *
		 * @param string      $name    The name of the block or the name of the property
		 * @param string|null $type    The type of the block or null if name is a property
		 * @param array       $options The options
		 *
		 * @return BlockBuilderInterface The created builder
		 */
		public function create($name, $type = null, array $options = array());

		/**
		 * Returns a child by name.
		 *
		 * @param string $name The name of the child
		 *
		 * @return BlockBuilderInterface The builder for the child
		 *
		 * @throws Exception\InvalidArgumentException if the given child does not exist
		 */
		public function get($name);

		/**
		 * Removes the field with the given name.
		 *
		 * @param string $name
		 *
		 * @return BlockBuilderInterface The builder object
		 */
		public function remove($name);

		/**
		 * Returns whether a field with the given name exists.
		 *
		 * @param string $name
		 *
		 * @return bool
		 */
		public function has($name);

		/**
		 * Returns the children.
		 *
		 * @return array
		 */
		public function all();

		/**
		 * Creates the block.
		 *
		 * @return BlockInterface The block
		 */
		public function getBlock();
	}
