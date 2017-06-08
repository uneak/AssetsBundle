<?php

	namespace Uneak\AssetsBundle\LibraryType;


	use Symfony\Component\Config\Definition\Builder\NodeDefinition;
	use Uneak\AssetsBundle\AssetItem\Library\Library;

	class DefaultLibraryType implements LibraryTypeInterface {

		/**
		 * {@inheritdoc}
		 */
		public function addExtraConfiguration(NodeDefinition $node) {
			$node
				->children()
					->scalarNode('output_dir')
						->defaultValue('')
					->end()
					->scalarNode('input_dir')
						->defaultValue('')
					->end()
					->scalarNode('path')
						->defaultValue('')
					->end()
				->end();
			;
			return $node;
		}

		public function createAssetItem($name, $parent = null, array $options = array()) {
			return new Library($name, $parent, $options);
		}

		public function getAlias() {
			return 'default';
		}

	}