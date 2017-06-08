<?php

	namespace Uneak\AssetsBundle\DependencyInjection;

	use Symfony\Component\DependencyInjection\ContainerBuilder;
	use Symfony\Component\Config\FileLocator;
    use Symfony\Component\DependencyInjection\Definition;
    use Symfony\Component\HttpKernel\DependencyInjection\Extension;
	use Symfony\Component\DependencyInjection\Loader;
    use Uneak\AssetsBundle\Config\Configuration;

    /**
	 * This is the class that loads and manages your bundle configuration
	 *
	 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
	 */
	class UneakAssetsExtension extends Extension {

		/**
		 * {@inheritdoc}
		 */
		public function load(array $configs, ContainerBuilder $container) {

			$configuration = new Configuration();
			$config = $this->processConfiguration($configuration, $configs);

			$loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
			$loader->load('npm.yml');
			$loader->load('asset_loader.yml');
			$loader->load('asset_type.yml');
			$loader->load('assets.yml');
			$loader->load('bower.yml');
			$loader->load('form.yml');
			$loader->load('block.yml');
			$loader->load('bulk_process.yml');
			$loader->load('library_type.yml');
			$loader->load('webpack.yml');
			$loader->load('webpack_configuration.yml');
			$loader->load('webpack_extension.yml');

			array_walk($config['finder'], function(&$value){
				$value = array("path" => $value);
			});

			$container->setParameter('uneak.assets.finder.alias', $config['finder']);

			$container->setParameter('uneak.assets.npm_config', $config['npm']);

			if ($config) {
				$formThemes = array();
				$blockThemes = array();
				$this->registerThemes($config, $formThemes, $blockThemes);


				if (isset($config['packages']) && count($config['packages'])) {
					foreach ($config['packages'] as $package) {
						$this->registerThemes($package, $formThemes, $blockThemes);
					}
					$container->setParameter('uneak.assets.packages_config', $config['packages']);
				}
				$container->setParameter('uneak.assets.bower_config', $config["bower"]);
				$container->setParameter('uneak.assets.prefix_config', array(
					"input_dir" => $config["input_dir"],
					"output_dir" => $config["output_dir"],
					"path" => $config["path"],
				));


				$resources = $container->hasParameter('twig.form.resources') ? $container->getParameter('twig.form.resources') : array();
				foreach ($formThemes as $formTheme) {
					$resources[] = $formTheme;
				}

				$container->setParameter('twig.form.resources', $resources);
				$container->setParameter('twig.block.resources', $blockThemes);

			}

		}


		static public function registerThemes(array $source, array &$formThemes, array &$blockThemes) {
			if (isset($source['themes'])) {
				if (isset($source['themes']['block']) && $source['themes']['block']) {
					foreach ($source['themes']['block'] as $theme) {
						$blockThemes[] = $theme;
					}
				}
				if (isset($source['themes']['form']) && $source['themes']['form']) {
					foreach ($source['themes']['form'] as $theme) {
						$formThemes[] = $theme;
					}
				}
			}
		}

	}
