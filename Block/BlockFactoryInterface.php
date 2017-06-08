<?php

	namespace Uneak\AssetsBundle\Block;

	use Uneak\AssetsBundle\Assets\Assets;

	interface BlockFactoryInterface {
		/**
		 * @return AbstractRendererEngine
		 */
		public function getRendererEngine();

		/**
		 * @return \Uneak\AssetsBundle\Pool\AssetInclude
		 */
		public function getAssetInclude();

		/**
		 * @return Assets
		 */
		public function getAssets();

		/**
		 * Returns a block.
		 *
		 * @see createBuilder()
		 *
		 * @param string $type    The type of the block
		 * @param mixed  $data    The initial data
		 * @param array  $options The options
		 *
		 * @return BlockInterface The block named after the type
		 *
		 * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException if any given option is not
		 *                                                                              applicable to the given type
		 */
		public function create($type = 'Uneak\AssetsBundle\Block\Extension\Core\Type\BlockType', $data = null, array $options = array());

		/**
		 * Returns a block.
		 *
		 * @see createNamedBuilder()
		 *
		 * @param string|int $name    The name of the block
		 * @param string     $type    The type of the block
		 * @param mixed      $data    The initial data
		 * @param array      $options The options
		 *
		 * @return BlockInterface The block
		 *
		 * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException if any given option is not
		 *                                                                              applicable to the given type
		 */
		public function createNamed($name, $type = 'Uneak\AssetsBundle\Block\Extension\Core\Type\BlockType', $data = null, array $options = array());

		/**
		 * Returns a block for a property of a class.
		 *
		 * @see createBuilderForProperty()
		 *
		 * @param string $class    The fully qualified class name
		 * @param string $property The name of the property to guess for
		 * @param mixed  $data     The initial data
		 * @param array  $options  The options for the builder
		 *
		 * @return BlockInterface The block named after the property
		 *
		 * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException if any given option is not
		 *                                                                              applicable to the block type
		 */
		public function createForProperty($class, $property, $data = null, array $options = array());

		/**
		 * Returns a block builder.
		 *
		 * @param string $type    The type of the block
		 * @param mixed  $data    The initial data
		 * @param array  $options The options
		 *
		 * @return BlockBuilderInterface The block builder
		 *
		 * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException if any given option is not
		 *                                                                              applicable to the given type
		 */
		public function createBuilder($type = 'Uneak\AssetsBundle\Block\Extension\Core\Type\BlockType', $data = null, array $options = array());

		/**
		 * Returns a block builder.
		 *
		 * @param string|int $name    The name of the block
		 * @param string     $type    The type of the block
		 * @param mixed      $data    The initial data
		 * @param array      $options The options
		 *
		 * @return BlockBuilderInterface The block builder
		 *
		 * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException if any given option is not
		 *                                                                              applicable to the given type
		 */
		public function createNamedBuilder($name, $type = 'Uneak\AssetsBundle\Block\Extension\Core\Type\BlockType', $data = null, array $options = array());

		/**
		 * Returns a block builder for a property of a class.
		 *
		 * If any of the 'required' and type options can be guessed,
		 * and are not provided in the options argument, the guessed value is used.
		 *
		 * @param string $class    The fully qualified class name
		 * @param string $property The name of the property to guess for
		 * @param mixed  $data     The initial data
		 * @param array  $options  The options for the builder
		 *
		 * @return BlockBuilderInterface The block builder named after the property
		 *
		 * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException if any given option is not
		 *                                                                              applicable to the block type
		 */
		public function createBuilderForProperty($class, $property, $data = null, array $options = array());
	}
