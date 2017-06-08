<?php

	namespace Uneak\AssetsBundle\Assets\BulkProcess;


	use Uneak\AssetsBundle\AssetItem\Asset\Asset;
	use Uneak\AssetsBundle\AssetItem\Bulk\BulkInterface;
	use Uneak\AssetsBundle\Assets\AbstractBulkProcess;
	use Uneak\AssetsBundle\AssetType\AssetTypeManager;


	class ResolveAssetsTypeBulkProcess extends AbstractBulkProcess {

		/**
		 * @var \Uneak\AssetsBundle\AssetType\AssetTypeManager
		 */
		private $assetTypeManager;

		public function __construct(AssetTypeManager $assetTypeManager) {
			$this->assetTypeManager = $assetTypeManager;
		}

		public function process(BulkInterface $bulk) {
			/** @var $asset Asset */
			foreach ($bulk->assets()->all() as $asset) {
				if (!$asset->getType()) {
					$guesser = $this->assetTypeManager->getTypeGuesser();
					$guess = $guesser->guessAsset($asset);
					if ($guess) {
						$asset->setType($guess->getType());
					}
				}
			}
		}

		
	}