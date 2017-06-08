<?php

	namespace Uneak\AssetsBundle\Config;

	use Symfony\Component\Config\Definition\Builder\NodeBuilder;
	use Symfony\Component\Config\Definition\Builder\TreeBuilder;
	use Symfony\Component\Config\Definition\ConfigurationInterface;
	use Symfony\Component\Process\ExecutableFinder;

	/**
	 * This is the class that validates and merges configuration from your app/config files
	 *
	 * To learn more see {@link
	 * http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
	 */
	class Configuration implements ConfigurationInterface {
		/**
		 * {@inheritdoc}
		 */
		public function getConfigTreeBuilder() {
			$finder = new ExecutableFinder();

			$treeBuilder = new TreeBuilder();
			$rootNode = $treeBuilder->root('uneak_assets');
			$rootNode
				->children()
					->arrayNode('finder')
						->useAttributeAsKey('name')
						->prototype('scalar')->end()
					->end()
					->arrayNode('npm')
						->addDefaultsIfNotSet()
						->children()
							->scalarNode('bin')
								->info('npm binary path')
								->defaultValue($finder->find('npm', '/usr/bin/npm'))
							->end()
						->end()
					->end()
					->arrayNode('bower')
						->addDefaultsIfNotSet()
						->children()
							->booleanNode('allow_root')
								->defaultFalse()
							->end()
							->scalarNode('bin')
								->defaultValue($finder->find('bower', '/usr/bin/bower'))
							->end()
							->scalarNode('registry')
								->defaultValue('https://bower.herokuapp.com')
							->end()
						->end()
					->end()
					->scalarNode('output_dir')
						->defaultValue('%kernel.root_dir%/../web/assets')
					->end()
					->scalarNode('input_dir')
						->defaultValue('%kernel.root_dir%/..')
					->end()
					->scalarNode('path')
						->defaultValue('assets')
					->end()

					->arrayNode('packages')
						->useAttributeAsKey('name')
						->prototype('array')
							->beforeNormalization()
								->ifString()->then(function($v) { return array('resource' => $v); })->end()
							->children()
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
									->defaultValue(array())
								->end()
								->scalarNode('resource')->end()
							->end()
						->end()
					->end()
				->end();
			;

			return $treeBuilder;
		}

	}
