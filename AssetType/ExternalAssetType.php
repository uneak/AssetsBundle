<?php

namespace Uneak\AssetsBundle\AssetType;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Uneak\AssetsBundle\AssetItem\Asset\ExternalAsset;

abstract class ExternalAssetType extends AssetType {

    protected function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefined(array('class'));
    }

    public function addExtraConfiguration(NodeDefinition $node) {
        $node
            ->children()
            ->end()
        ;
        return $node;
    }

    public function createAssetItem($name, $parent = null, array $options = array()) {
        return new ExternalAsset($name, $parent, $options);
    }
    
}