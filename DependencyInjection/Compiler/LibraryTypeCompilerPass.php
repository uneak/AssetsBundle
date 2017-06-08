<?php

	namespace Uneak\AssetsBundle\DependencyInjection\Compiler;

	use Symfony\Component\DependencyInjection\ContainerBuilder;
	use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
	use Symfony\Component\DependencyInjection\Reference;

	class LibraryTypeCompilerPass implements CompilerPassInterface {

		public function process(ContainerBuilder $container) {
			$definition = $container->getDefinition('uneak.assets.library_type.manager');
			$taggedServices = $container->findTaggedServiceIds('uneak.assets.library_type');

			foreach ($taggedServices as $serviceId => $tag) {
				foreach ($tag as $attributes) {
					$definition->addMethodCall(
						'addLibraryType', array(new Reference($serviceId))
					);
				}
			}

		}

	}
