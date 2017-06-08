<?php

	namespace Uneak\AssetsBundle\DependencyInjection\Compiler;

	use Symfony\Component\DependencyInjection\ContainerBuilder;
	use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
	use Symfony\Component\DependencyInjection\Reference;

	class WebpackCompilerPass implements CompilerPassInterface {

		public function process(ContainerBuilder $container) {
			$definition = $container->getDefinition('uneak.webpack.manager');
			$taggedServices = $container->findTaggedServiceIds('uneak.webpack');

			foreach ($taggedServices as $serviceId => $tagAttributes) {
				foreach ($tagAttributes as $attributes) {
					$definition->addMethodCall(
						'addWebpack', array(new Reference($serviceId))
					);
				}
			}
		}

	}
