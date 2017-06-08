<?php

	namespace Uneak\AssetsBundle\Config;

	use Symfony\Component\Config\Definition\Builder\NodeDefinition;
	use Symfony\Component\Config\Definition\Builder\NodeParentInterface;
	use Symfony\Component\Config\Definition\Builder\TreeBuilder;
	use Symfony\Component\Config\Definition\ConfigurationInterface;

	/**
	 * This is the class that validates and merges configuration from your app/config files
	 *
	 * To learn more see {@link
	 * http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
	 */
	class AssetsConfiguration implements ConfigurationInterface {
		/**
		 * {@inheritdoc}
		 */
		public function getConfigTreeBuilder() {
			$treeBuilder = new TreeBuilder();
			AssetsConfiguration::assetsNode($treeBuilder);

			return $treeBuilder;
		}


		public static function assetsNode(NodeParentInterface $parent = null) {
			if (!$parent) {
				$parent = new TreeBuilder();
			}
			$node = $parent->root('assets');
			$node
				->beforeNormalization()
					->ifString()->then(function($v) { return array($v); })->end()
				->useAttributeAsKey('name')
				->prototype('variable')
					->beforeNormalization()
						->ifString()->then(function($v) { return array('resource' => $v); })->end()
				->end()
			;
			return $node;
		}


		public static function addAssetConfiguration(NodeDefinition $node) {
			$node
				->children()
					->scalarNode('resource')->end()
					->scalarNode('type')->end()
					->scalarNode('section')->end()
					->scalarNode('file')->end()
					->arrayNode('dependencies')
						->prototype('scalar')->end()
					->end()

					->scalarNode('output_dir')
						->defaultNull()
					->end()
					->scalarNode('input_dir')
						->defaultNull()
					->end()
					->scalarNode('path')
						->defaultNull()
					->end()
					->variableNode('parameters')
						->defaultNull()
					->end()
			
				->end()
			;
			return $node;
		}
		
		

		public function getRootName() {
			return 'assets';
		}
		
		
	}