<?php

	namespace Uneak\AssetsBundle\AssetType\Guesser;

	use Uneak\AssetsBundle\AssetItem\Asset\Asset;
	use Uneak\AssetsBundle\AssetType\AssetTypeGuesserInterface;
	use Uneak\AssetsBundle\AssetType\Guess\Guess;
	use Uneak\AssetsBundle\AssetType\Guess\TypeGuess;

	class PathGuesser implements AssetTypeGuesserInterface {

		/**
		 * @var
		 */
		private $type;
		/**
		 * @var array
		 */
		private $except;

		public function __construct($type, array $except) {
			$this->type = $type;
			$this->except = $except;
		}

		/**
		 * @param $asset    Asset
		 *
		 * @return TypeGuess
		 */
		public function guessAsset(Asset $asset) {
			if ($asset->getFile()) {
				$check = preg_match('/^.*\.('.implode('|', $this->except).')$/i', $asset->getFile());
				if ($check) {
					return new TypeGuess($this->type, Guess::VERY_HIGH_CONFIDENCE);
				}
			}
			return null;
		}

	}
