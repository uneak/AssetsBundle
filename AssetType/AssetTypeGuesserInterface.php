<?php

	namespace Uneak\AssetsBundle\AssetType;

	use Uneak\AssetsBundle\AssetItem\Asset\Asset;
	use Uneak\AssetsBundle\AssetType\Guess\TypeGuess;

	interface AssetTypeGuesserInterface {

		/**
		 * @param $asset    Asset
		 *
		 * @return TypeGuess
		 */
		public function guessAsset(Asset $asset);

	}
