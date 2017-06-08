<?php

namespace Uneak\AssetsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;


class AssetTypePass implements CompilerPassInterface {

    public function process(ContainerBuilder $container) {
        if (false === $container->hasDefinition('uneak.assets.asset_type.manager')) {
            return;
        }
        $definition = $container->getDefinition('uneak.assets.asset_type.manager');

		$taggedTypeServices = $container->findTaggedServiceIds('uneak.assets.asset_type');
		foreach ($taggedTypeServices as $id => $tagAttributes) {
			foreach ($tagAttributes as $attributes) {
				$definition->addMethodCall(
					'setAssetType', array(new Reference($id))
				);
			}
		}

		$taggedTypeGuesserServices = $container->findTaggedServiceIds('uneak.assets.asset_type.guesser');
		foreach ($taggedTypeGuesserServices as $id => $tagAttributes) {
			foreach ($tagAttributes as $attributes) {
				$definition->addMethodCall(
					'addTypeGuesser', array(new Reference($id))
				);
			}
		}




	}
}
