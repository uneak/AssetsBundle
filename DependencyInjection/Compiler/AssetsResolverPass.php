<?php

	namespace Uneak\AssetsBundle\DependencyInjection\Compiler;

	use Symfony\Component\DependencyInjection\Reference;
	use Symfony\Component\DependencyInjection\ContainerBuilder;
	use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

	class AssetsResolverPass implements CompilerPassInterface {
		public function process(ContainerBuilder $container) {
			if (false === $container->hasDefinition('uneak.assets.resolver')) {
				return;
			}

			$definition = $container->getDefinition('uneak.assets.resolver');

			foreach ($container->findTaggedServiceIds('uneak.assets.loader') as $id => $attributes) {
				$definition->addMethodCall('addLoader', array(new Reference($id)));
			}
		}
	}
