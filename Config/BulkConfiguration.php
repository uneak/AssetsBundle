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
	class BulkConfiguration implements ConfigurationInterface {
		/**
		 * {@inheritdoc}
		 */
		public function getConfigTreeBuilder() {
			$treeBuilder = new TreeBuilder();
			$rootNode = $treeBuilder->root($this->getRootName());
			$rootNode
				->children()
					->scalarNode('tag')->end()
					->append(PackageConfiguration::packagesNode())
					->append(LibrariesConfiguration::librariesNode())
					->append(AssetsConfiguration::assetsNode())
				->end()
			;

			return $treeBuilder;
		}

		public function getRootName() {
			return 'package_resources';
		}
	}
