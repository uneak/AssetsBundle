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
	class LibrariesConfiguration implements ConfigurationInterface {
		/**
		 * {@inheritdoc}
		 */
		public function getConfigTreeBuilder() {
			$treeBuilder = new TreeBuilder();
			LibrariesConfiguration::librariesNode($treeBuilder);
			return $treeBuilder;
		}

		public static function librariesNode(NodeParentInterface $parent = null) {
			if (!$parent) {
				$parent = new TreeBuilder();
			}
	
			$node = $parent->root('libraries');
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


		public static function addLibraryConfiguration(NodeDefinition $node) {
			$node
				->children()
					->scalarNode('resource')->end()
					->scalarNode('type')
						->defaultValue('default')
					->end()
					->variableNode('parameters')
						->defaultValue(array())
					->end()
					->arrayNode('main')
						->prototype('scalar')->end()
					->end()
					 ->arrayNode('dependencies')
						->prototype('scalar')->end()
					->end()

//						->append(ThemeConfiguration::themesNode())
					->append(AssetsConfiguration::assetsNode())
				->end()
			;
			return $node;
		}




		public function getRootName() {
			return 'libraries';
		}

	}
