<?php

namespace Uneak\AssetsBundle\AssetType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Uneak\AssetsBundle\AssetItem\Asset\CssExternalAsset;
use Uneak\AssetsBundle\AssetType\Guesser\PathGuesser;
use Uneak\AssetsBundle\Pool\AssetPoolItem;
use Uneak\AssetsBundle\Pool\Pool;


class CssExternalAssetType extends ExternalAssetType {

    protected function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);
		$resolver->setDefined(array('rel', 'type', 'media', 'title'));
		$resolver->setRequired('type');
		$resolver->setDefaults(array(
			"type" => "text/css",
			"rel" => "stylesheet",
			"tag" => "link",
		));
    }

	public function getTypeGuesser() {
		return array(
//			new JsExternalAssetTypeGuesser(),
			new PathGuesser($this->getAlias(), array("css"))
		);
	}

	public function buildPool(Pool $pool, $id, $data, array $sections = array()) {
		$sections[] = $this->getAlias();

		if ($pool->has($id)) {
			$poolItem = $pool->get($id);
		} else {
			$poolItem = new AssetPoolItem($id);
		}
		$poolItem->setData($data);
		$pool->set($id, $sections, $poolItem);

		return $poolItem;
	}

	public function getRenderData($data) {
		return array(
			'url' => $data->getPath(),
			'context' => $this->resolve($data->getParameters())
		);
	}

	public function render(\Twig_Environment $twig, $data) {
		return $twig->render("@UneakAssets/AssetType/stylesheets.html.twig", $data);
	}

	public function getAlias() {
		return "stylesheets";
	}

	public function createAssetItem($name, $parent = null, array $options = array()) {
		return new CssExternalAsset($name, $parent, $options);
	}
	
}