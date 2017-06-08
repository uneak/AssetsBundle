<?php

	namespace Uneak\AssetsBundle\AssetType;

	use Uneak\AssetsBundle\AssetItem\Asset\Asset;
	use Uneak\AssetsBundle\AssetType\Guess\Guess;
	use Uneak\AssetsBundle\AssetType\Guess\TypeGuess;

	class ImageExternalAssetTypeGuesser implements AssetTypeGuesserInterface {

		/**
		 * @param $asset    Asset
		 *
		 * @return TypeGuess
		 */
		public function guessAsset(Asset $asset) {

			if (isset($asset['path'])) {
				$except = array("jpg", "jpeg", "png", "gif", "bmp");
				$check = preg_match('/^.*\.('.implode('|', $except).')$/i', $asset['path']);

				if ($check) {
					return new TypeGuess("image", Guess::VERY_HIGH_CONFIDENCE);
				}
			}

			return null;
		}

		/**
		 * @param $renderData    RenderDataInterface
		 * 
		 *
		 * @return TypeGuess
		 */
		public function guessRenderData(RenderDataInterface $renderData) {
			// TODO: Implement guessRenderData() method.
		}
	}
