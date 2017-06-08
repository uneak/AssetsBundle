<?php

namespace Uneak\AssetsBundle\AssetType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Uneak\AssetsBundle\AssetItem\Asset\ImageExternalAsset;
use Uneak\AssetsBundle\AssetType\Guesser\PathGuesser;
use Uneak\AssetsBundle\Pool\AssetPoolItem;
use Uneak\AssetsBundle\Pool\Pool;


class ImageExternalAssetType extends ExternalAssetType {

    protected function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);
        $resolver->setDefined(array('tag', 'alt', 'crossorigin', 'height', 'ismap', 'longdesc', 'usemap', 'width'));
        $resolver->setDefaults(array(
            "tag" => "img",
        ));
    }

	public function getTypeGuesser() {
		return array(
//			new ImageExternalAssetTypeGuesser(),
			new PathGuesser($this->getAlias(), array("jpg", "jpeg", "png", "gif", "bmp"))
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
			'url' => $data['url'],
			'context' => $this->resolve($data->getParameters())
		);
	}
	public function render(\Twig_Environment $twig, $data) {
		return $twig->render("@UneakAssets/AssetType/image.html.twig", $data);
	}

	public function getAlias() {
		return "image";
	}


	public function createAssetItem($name, $parent = null, array $options = array()) {
		return new ImageExternalAsset($name, $parent, $options);
	}
	



}