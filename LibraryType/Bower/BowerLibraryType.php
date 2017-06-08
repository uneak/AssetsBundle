<?php

	namespace Uneak\AssetsBundle\LibraryType\Bower;

	use Symfony\Component\Config\Definition\Builder\NodeDefinition;
	use Uneak\AssetsBundle\AssetItem\Library\BowerLibrary;
	use Uneak\AssetsBundle\LibraryType\LibraryTypeInterface;

	class BowerLibraryType implements LibraryTypeInterface {

		/**
		 * {@inheritdoc}
		 */
		public function addExtraConfiguration(NodeDefinition $node) {
			$node
				->children()
					->arrayNode('endpoint')
						->prototype('scalar')->end()
					->end()
				->end();
			;
			return $node;
		}

		public function createAssetItem($name, $parent = null, array $options = array()) {
			return new BowerLibrary($name, $parent, $options);
		}

		public function getAlias() {
			return 'bower';
		}

	}