<?php

namespace Uneak\AssetsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;


class AssetsPackagePass implements CompilerPassInterface {

    public function process(ContainerBuilder $container) {
        if (false === $container->hasDefinition('uneak.assets.package') ||
            false === $container->hasDefinition('assets.packages')
        ) {
            return;
        }


        $assetsDefinition = $container->getDefinition('uneak.assets.package');
        $assetsPackagesDefinition = $container->getDefinition('assets.packages');

        $assetsPackagesDefinition->addMethodCall('addPackage', array('assets', $assetsDefinition));

    }
}
