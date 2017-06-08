<?php

namespace Uneak\AssetsBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Uneak\AssetsBundle\DependencyInjection\Compiler\AssetsPackagePass;
use Uneak\AssetsBundle\DependencyInjection\Compiler\AssetsResolverPass;
use Uneak\AssetsBundle\DependencyInjection\Compiler\AssetTypePass;
use Uneak\AssetsBundle\DependencyInjection\Compiler\BlockPass;
use Uneak\AssetsBundle\DependencyInjection\Compiler\BulkProcessCompilerPass;
use Uneak\AssetsBundle\DependencyInjection\Compiler\LibraryTypeCompilerPass;
use Uneak\AssetsBundle\DependencyInjection\Compiler\WebpackCompilerPass;

class UneakAssetsBundle extends Bundle {

    public function build(ContainerBuilder $container) {
        parent::build($container);
        $container->addCompilerPass(new AssetsResolverPass());
        $container->addCompilerPass(new AssetTypePass());
        $container->addCompilerPass(new AssetsPackagePass());
        $container->addCompilerPass(new BlockPass());
        $container->addCompilerPass(new BulkProcessCompilerPass());
        $container->addCompilerPass(new LibraryTypeCompilerPass());
        $container->addCompilerPass(new WebpackCompilerPass());
    }

}
