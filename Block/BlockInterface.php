<?php

	namespace Uneak\AssetsBundle\Block;

	interface BlockInterface extends \ArrayAccess, \Traversable, \Countable {
		/**
		 * Sets the parent block.
		 *
		 * @param BlockInterface|null $parent The parent block or null if it's the root
		 *
		 * @return BlockInterface The block instance
		 *
		 * @throws Exception\LogicException            When trying to set a parent for a block with
		 *                                             an empty name.
		 */
		public function setParent(BlockInterface $parent = null);

		/**
		 * Returns the parent block.
		 *
		 * @return BlockInterface|null The parent block or null if there is none
		 */
		public function getParent();

		/**
		 * Adds or replaces a child to the block.
		 *
		 * @param BlockInterface|string|int $child   The BlockInterface instance or the name of the child
		 * @param string|null              $type    The child's type, if a name was passed
		 * @param array                    $options The child's options, if a name was passed
		 *
		 * @return BlockInterface The block instance
		 *
		 * @throws Exception\UnexpectedTypeException   If $child or $type has an unexpected type.
		 */
		public function add($child, $type = null, array $options = array());

		/**
		 * Returns the child with the given name.
		 *
		 * @param string $name The name of the child
		 *
		 * @return BlockInterface The child block
		 *
		 * @throws \OutOfBoundsException If the named child does not exist.
		 */
		public function get($name);

		/**
		 * Returns whether a child with the given name exists.
		 *
		 * @param string $name The name of the child
		 *
		 * @return bool
		 */
		public function has($name);

		/**
		 * Removes a child from the block.
		 *
		 * @param string $name The name of the child to remove
		 *
		 * @return BlockInterface The block instance
		 *
		 */
		public function remove($name);

		/**
		 * Returns all children in this group.
		 *
		 * @return BlockInterface[] An array of BlockInterface instances
		 */
		public function all();

		/**
		 * Updates the block with default data.
		 *
		 * @param mixed $modelData The data formatted as expected for the underlying object
		 *
		 * @return BlockInterface The block instance
		 *
		 * @throws Exception\LogicException            If listeners try to call setData in a cycle. Or if
		 *                                             the view data does not match the expected type
		 *                                             according to {@link BlockConfigInterface::getDataClass}.
		 */
		public function setData($modelData);

		/**
		 * Returns the data in the blockat needed for the underlying object.
		 *
		 * @return mixed
		 */
		public function getData();

		/**
		 * Returns the normalized data of the field.
		 *
		 * @return mixed When the field is not submitted, the default data is returned
		 *               When the field is submitted, the normalized submitted data is
		 *               returned if the field is valid, null otherwise.
		 */
		public function getNormData();

		/**
		 * Returns the data transformed by the value transformer.
		 *
		 * @return mixed
		 */
		public function getViewData();

		/**
		 * Returns the block's configuration.
		 *
		 * @return BlockConfigInterface The configuration
		 */
		public function getConfig();

		/**
		 * Returns the name by which the block is identified in blocks.
		 *
		 * @return string The name of the block
		 */
		public function getName();

		/**
		 * Returns whether the block is empty.
		 *
		 * @return bool
		 */
		public function isEmpty();

		/**
		 * Initializes the block tree.
		 *
		 * Should be called on the root block after constructing the tree.
		 *
		 * @return BlockInterface The block instance
		 */
		public function initialize();


		/**
		 * Returns the root of the block tree.
		 *
		 * @return BlockInterface The root of the tree
		 */
		public function getRoot();

		/**
		 * Returns whether the field is the root of the block tree.
		 *
		 * @return bool
		 */
		public function isRoot();

		/**
		 * Returns the property path that the form is mapped to.
		 *
		 * @return \Symfony\Component\PropertyAccess\PropertyPathInterface The property path
		 */
		public function getPropertyPath();

		/**
		 * Creates a view.
		 *
		 * @param BlockView $parent The parent view
		 *
		 * @return BlockView The view
		 */
		public function createView(BlockView $parent = null);
	}
