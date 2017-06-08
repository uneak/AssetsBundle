<?php

	namespace Uneak\AssetsBundle\AssetItem\Package;

	use Uneak\AssetsBundle\AssetItem\AssetItem;

	class Package extends AssetItem {
		/**
		 * @return string
		 */
		public function getId() {
			return '@' . $this->name;
		}

	}