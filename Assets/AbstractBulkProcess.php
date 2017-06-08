<?php

	namespace Uneak\AssetsBundle\Assets;

	use Uneak\AssetsBundle\AssetItem\Bulk\BulkInterface;

	abstract class AbstractBulkProcess implements BulkProcessInterface {
		
		abstract public function process(BulkInterface $bulk);
		public function check(BulkInterface $bulk, BulkInterface $processedBulk) { }
		
	}