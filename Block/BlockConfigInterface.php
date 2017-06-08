<?php

	namespace Uneak\AssetsBundle\Block;

	use Symfony\Component\EventDispatcher\EventDispatcherInterface;
	use Symfony\Component\PropertyAccess\PropertyPathInterface;

	/**
	 * The configuration of a {@link Block} object.
	 *
	 */
	interface BlockConfigInterface {
		/**
		 * Returns the event dispatcher used to dispatch block events.
		 *
		 * @return EventDispatcherInterface The dispatcher
		 */
		public function getEventDispatcher();

		/**
		 * Returns the name of the block used as HTTP parameter.
		 *
		 * @return string The block name
		 */
		public function getName();

		/**
		 * Returns the property path that the block should be mapped to.
		 *
		 * @return null|PropertyPathInterface The property path
		 */
		public function getPropertyPath();


		/**
		 * Returns whether the block should read and write the data of its parent.
		 *
		 * @return bool Whether the block should inherit its parent's data
		 */
		public function getInheritData();

		/**
		 * Returns the block types used to construct the block.
		 *
		 * @return ResolvedBlockTypeInterface The block's type
		 */
		public function getType();

		/**
		 * Returns the view transformers of the block.
		 *
		 * @return DataTransformerInterface[] An array of {@link DataTransformerInterface} instances
		 */
		public function getViewTransformers();

		/**
		 * Returns the model transformers of the block.
		 *
		 * @return DataTransformerInterface[] An array of {@link DataTransformerInterface} instances
		 */
		public function getModelTransformers();

		/**
		 * Returns the data mapper of the block.
		 *
		 * @return DataMapperInterface The data mapper
		 */
		public function getDataMapper();

		/**
		 * Returns the data that should be returned when the block is empty.
		 *
		 * @return mixed The data returned if the block is empty
		 */
		public function getEmptyData();

		/**
		 * Returns additional attributes of the block.
		 *
		 * @return array An array of key-value combinations
		 */
		public function getAttributes();

		/**
		 * Returns whether the attribute with the given name exists.
		 *
		 * @param string $name The attribute name
		 *
		 * @return bool Whether the attribute exists
		 */
		public function hasAttribute($name);

		/**
		 * Returns the value of the given attribute.
		 *
		 * @param string $name    The attribute name
		 * @param mixed  $default The value returned if the attribute does not exist
		 *
		 * @return mixed The attribute value
		 */
		public function getAttribute($name, $default = null);

		/**
		 * Returns the initial data of the block.
		 *
		 * @return mixed The initial block data
		 */
		public function getData();

		/**
		 * Returns the class of the block data or null if the data is scalar or an array.
		 *
		 * @return string The data class or null
		 */
		public function getDataClass();

		/**
		 * Returns whether the block's data is locked.
		 *
		 * A block with locked data is restricted to the data passed in
		 * this configuration. The data can only be modified then by
		 * submitting the block.
		 *
		 * @return bool Whether the data is locked
		 */
		public function getDataLocked();

		/**
		 * Returns the block factory used for creating new blocks.
		 *
		 * @return BlockFactoryInterface The block factory
		 */
		public function getBlockFactory();

		/**
		 * Returns whether the block should be initialized upon creation.
		 *
		 * @return bool Returns true if the block should be initialized
		 *              when created, false otherwise.
		 */
		public function getAutoInitialize();

		/**
		 * Returns all options passed during the construction of the block.
		 *
		 * @return array The passed options
		 */
		public function getOptions();

		/**
		 * Returns whether a specific option exists.
		 *
		 * @param string $name The option name,
		 *
		 * @return bool Whether the option exists
		 */
		public function hasOption($name);

		/**
		 * Returns the value of a specific option.
		 *
		 * @param string $name    The option name
		 * @param mixed  $default The value returned if the option does not exist
		 *
		 * @return mixed The option value
		 */
		public function getOption($name, $default = null);
	}
