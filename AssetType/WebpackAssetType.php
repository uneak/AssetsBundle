<?php

namespace Uneak\AssetsBundle\AssetType;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Uneak\AssetsBundle\AssetItem\Asset\WebpackAsset;


class WebpackAssetType extends JsExternalAssetType {

	public function getTypeGuesser() {
		return array();
	}

	public function getAlias() {
		return "webpack";
	}

	public function addExtraConfiguration(NodeDefinition $node) {
		$node
			->children()
				->scalarNode('input')
					->isRequired()
					->cannotBeEmpty()
				->end()
				->scalarNode('input_dir')
					->defaultValue('')
				->end()
				->scalarNode('output')
					->isRequired()
					->cannotBeEmpty()
				->end()
				->scalarNode('output_dir')
					->defaultValue('')
				->end()
				->scalarNode('config')
					->defaultValue('webpack')
				->end()
			->end()
		;
		return $node;
	}

	public function createAssetItem($name, $parent = null, array $options = array()) {
		return new WebpackAsset($name, $parent, $options);
	}
}