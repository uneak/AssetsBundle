<?php

	namespace Uneak\AssetsBundle\Config;

	use Symfony\Component\Config\Definition\Builder\NodeParentInterface;
	use Symfony\Component\Config\Definition\Builder\TreeBuilder;
	use Symfony\Component\Config\Definition\ConfigurationInterface;

	/**
	 * This is the class that validates and merges configuration from your app/config files
	 *
	 * To learn more see {@link
	 * http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
	 */
	class PackageConfiguration implements ConfigurationInterface {
		/**
		 * {@inheritdoc}
		 */
		public function getConfigTreeBuilder() {
			$treeBuilder = new TreeBuilder();
			PackageConfiguration::packagesNode($treeBuilder);
			return $treeBuilder;
		}


		public static function packagesNode(NodeParentInterface $parent = null) {
			if (!$parent) {
				$parent = new TreeBuilder();
			}

			$node = $parent->root('packages');
			$node
				->beforeNormalization()
					->ifString()->then(function($v) { return array($v); })->end()

				->useAttributeAsKey('name')
				->prototype('array')
					->beforeNormalization()
						->ifString()->then(function($v) { return array('resource' => $v); })->end()

					->children()
						->scalarNode('resource')->end()
//						->append(ThemeConfiguration::themesNode())
					->end()


				->end()

			;

			return $node;
		}


		public function getRootName() {
			return 'packages';
		}


	}
