<?php

namespace Uneak\AssetsBundle\AssetType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Uneak\AssetsBundle\AssetItem\Asset\JsInternalAsset;
use Uneak\AssetsBundle\AssetItem\AssetItemCollectionInterface;
use Uneak\AssetsBundle\Pool\AssetCollectionPoolItem;
use Uneak\AssetsBundle\Pool\Pool;


class JsInternalAssetType extends InternalAssetType {

    protected function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);
        $resolver->setDefined(array('type', 'charset', 'async', 'defer'));
        $resolver->setDefaults(array(
            "type" => "text/javascript",
            "tag" => "script",
        ));
    }


    public function buildPool(Pool $pool, $id, $data, array $sections = array()) {
		$sections[] = $this->getAlias();

		if ($pool->has($id)) {
			$poolItem = $pool->get($id);
		} else {
			$poolItem = new AssetCollectionPoolItem($id);
		}
		/** @var $assets AssetItemCollectionInterface */
		$assets = $poolItem->getData();
		$assets->add($data);

		$pool->set($id, $sections, $poolItem);

		return $poolItem;
    }

	public function getRenderData($data) {
		return array(
			'context' => $this->resolve($data->getParameters())
		);
	}

	public function render(\Twig_Environment $twig, $data) {
		return $twig->render("@UneakAssets/AssetType/javascripts_script.html.twig", $data);
	}

	public function getAlias() {
		return "javascripts_script";
	}

	public function createAssetItem($name, $parent = null, array $options = array()) {
		return new JsInternalAsset($name, $parent, $options);
	}
}