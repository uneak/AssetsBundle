<?php

	namespace Uneak\AssetsBundle\LibraryType\Install;

	use Symfony\Component\Config\Definition\Builder\NodeDefinition;
	use Uneak\AssetsBundle\AssetItem\Library\InstallLibrary;
	use Uneak\AssetsBundle\LibraryType\LibraryTypeInterface;

	class InstallLibraryType implements LibraryTypeInterface {

		/**
		 * {@inheritdoc}
		 */
		public function addExtraConfiguration(NodeDefinition $node) {
			$node
				->children()
					->enumNode('method')
						->values(array('copy', 'relative', 'symlink'))
					->end()
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
			return new InstallLibrary($name, $parent, $options);
		}

		public function getAlias() {
			return 'install';
		}

	}