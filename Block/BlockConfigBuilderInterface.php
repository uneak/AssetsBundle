<?php

	namespace Uneak\AssetsBundle\Block;

	use Symfony\Component\EventDispatcher\EventSubscriberInterface;
	use Symfony\Component\PropertyAccess\PropertyPathInterface;


	interface BlockConfigBuilderInterface extends BlockConfigInterface {
		/**
		 * Adds an event listener to an event on this block.
		 *
		 * @param string   $eventName The name of the event to listen to
		 * @param callable $listener  The listener to execute
		 * @param int      $priority  The priority of the listener. Listeners
		 *                            with a higher priority are called before
		 *                            listeners with a lower priority.
		 *
		 * @return self The configuration object
		 */
		public function addEventListener($eventName, $listener, $priority = 0);

		/**
		 * Adds an event subscriber for events on this block.
		 *
		 * @param EventSubscriberInterface $subscriber The subscriber to attach
		 *
		 * @return self The configuration object
		 */
		public function addEventSubscriber(EventSubscriberInterface $subscriber);

		/**
		 * Appends / prepends a transformer to the view transformer chain.
		 *
		 * The transform method of the transformer is used to convert data from the
		 * normalized to the view format.
		 * The reverseTransform method of the transformer is used to convert from the
		 * view to the normalized format.
		 *
		 * @param DataTransformerInterface $viewTransformer
		 * @param bool                     $forcePrepend if set to true, prepend instead of appending
		 *
		 * @return self The configuration object
		 */
		public function addViewTransformer(DataTransformerInterface $viewTransformer, $forcePrepend = false);

		/**
		 * Clears the view transformers.
		 *
		 * @return self The configuration object
		 */
		public function resetViewTransformers();

		/**
		 * Prepends / appends a transformer to the normalization transformer chain.
		 *
		 * The transform method of the transformer is used to convert data from the
		 * model to the normalized format.
		 * The reverseTransform method of the transformer is used to convert from the
		 * normalized to the model format.
		 *
		 * @param DataTransformerInterface $modelTransformer
		 * @param bool                     $forceAppend if set to true, append instead of prepending
		 *
		 * @return self The configuration object
		 */
		public function addModelTransformer(DataTransformerInterface $modelTransformer, $forceAppend = false);

		/**
		 * Clears the normalization transformers.
		 *
		 * @return self The configuration object
		 */
		public function resetModelTransformers();

		/**
		 * Sets the value for an attribute.
		 *
		 * @param string $name  The name of the attribute
		 * @param mixed  $value The value of the attribute
		 *
		 * @return self The configuration object
		 */
		public function setAttribute($name, $value);

		/**
		 * Sets the attributes.
		 *
		 * @param array $attributes The attributes
		 *
		 * @return self The configuration object
		 */
		public function setAttributes(array $attributes);

		/**
		 * Sets the data mapper used by the block.
		 *
		 * @param DataMapperInterface $dataMapper
		 *
		 * @return self The configuration object
		 */
		public function setDataMapper(DataMapperInterface $dataMapper = null);

		/**
		 * Sets the data used for the client data when no value is submitted.
		 *
		 * @param mixed $emptyData The empty data
		 *
		 * @return self The configuration object
		 */
		public function setEmptyData($emptyData);

		/**
		 * Sets the property path that the block should be mapped to.
		 *
		 * @param null|string|PropertyPathInterface $propertyPath
		 *                                                        The property path or null if the path should be set
		 *                                                        automatically based on the block's name.
		 *
		 * @return self The configuration object
		 */
		public function setPropertyPath($propertyPath);

		/**
		 * Sets whether the block should read and write the data of its parent.
		 *
		 * @param bool $inheritData Whether the block should inherit its parent's data
		 *
		 * @return self The configuration object
		 */
		public function setInheritData($inheritData);

		/**
		 * Set the types.
		 *
		 * @param ResolvedBlockTypeInterface $type The type of the block
		 *
		 * @return self The configuration object
		 */
		public function setType(ResolvedBlockTypeInterface $type);

		/**
		 * Sets the initial data of the block.
		 *
		 * @param mixed $data The data of the block in application format
		 *
		 * @return self The configuration object
		 */
		public function setData($data);

		/**
		 * Locks the block's data to the data passed in the configuration.
		 *
		 * A block with locked data is restricted to the data passed in
		 * this configuration. The data can only be modified then by
		 * submitting the block.
		 *
		 * @param bool $locked Whether to lock the default data
		 *
		 * @return self The configuration object
		 */
		public function setDataLocked($locked);

		/**
		 * Sets the block factory used for creating new blocks.
		 *
		 * @param BlockFactoryInterface $blockFactory The block factory
		 */
		public function setBlockFactory(BlockFactoryInterface $blockFactory);

		/**
		 * Sets whether the block should be initialized automatically.
		 *
		 * Should be set to true only for root blocks.
		 *
		 * @param bool $initialize True to initialize the block automatically,
		 *                         false to suppress automatic initialization.
		 *                         In the second case, you need to call
		 *                         {@link BlockInterface::initialize()} manually.
		 *
		 * @return self The configuration object
		 */
		public function setAutoInitialize($initialize);

		/**
		 * Builds and returns the block configuration.
		 *
		 * @return BlockConfigInterface
		 */
		public function getBlockConfig();
	}
