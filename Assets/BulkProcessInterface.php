<?php

	namespace Uneak\AssetsBundle\Assets;
	
	use Uneak\AssetsBundle\AssetItem\Bulk\BulkInterface;

	interface BulkProcessInterface {
		public function process(BulkInterface $bulk);
		public function check(BulkInterface $bulk, BulkInterface $processedBulk);
	}
