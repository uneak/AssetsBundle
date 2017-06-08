<?php

namespace Uneak\AssetsBundle\AssetType;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Uneak\AssetsBundle\AssetItem\Asset\InternalAsset;


abstract class InternalAssetType extends AssetType {

    protected function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefined(array('content', 'template', 'template_data', 'class'));
		$resolver->setDefaults(array(
			"template_data" => array(),
		));
    }

	public function addExtraConfiguration(NodeDefinition $node) {
		$node
			->children()
				->scalarNode('content')
					->isRequired()
					->cannotBeEmpty()
				->end()
			->end()
		;
		return $node;
	}


	public function createAssetItem($name, $parent = null, array $options = array()) {
		return new InternalAsset($name, $parent, $options);
	}
    
}