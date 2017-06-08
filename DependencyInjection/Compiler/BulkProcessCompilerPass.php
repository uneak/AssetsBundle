<?php

	namespace Uneak\AssetsBundle\DependencyInjection\Compiler;

	use Symfony\Component\DependencyInjection\ContainerBuilder;
	use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
	use Symfony\Component\DependencyInjection\Reference;

	class BulkProcessCompilerPass implements CompilerPassInterface {

		public function process(ContainerBuilder $container) {
			$definition = $container->getDefinition('uneak.assets.bulk_process.manager');
			$taggedServices = $container->findTaggedServiceIds('uneak.bulk_process');

			foreach ($taggedServices as $serviceId => $tag) {
				foreach ($tag as $attributes) {
					$definition->addMethodCall(
						'addBulkProcess', array(
							new Reference($serviceId),
							(isset($attributes["priority"])) ? $attributes["priority"] : 0,
							(isset($attributes["alias"])) ? $attributes["alias"] : null,
						)
					);
				}
			}

		}

	}
