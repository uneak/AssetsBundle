<?php

	namespace Uneak\AssetsBundle\LibraryType;


	use Symfony\Component\Config\Definition\Builder\NodeDefinition;
	use Uneak\AssetsBundle\AssetItem\AssetItemInterface;

	interface LibraryTypeInterface {
		/**
		 * @return string
		 */
		public function getAlias();
		/**
		 * Generates the configuration tree builder.
		 *
		 * @param NodeDefinition $node
		 * @return NodeDefinition
		 */
		public function addExtraConfiguration(NodeDefinition $node);
		/**
		 * @param       $name
		 * @param null  $parent
		 * @param array $options
		 *
		 * @return AssetItemInterface
		 */
		public function createAssetItem($name, $parent = null, array $options = array());
	}
