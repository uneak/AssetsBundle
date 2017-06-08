<?php

	namespace Uneak\AssetsBundle\AssetType;

	use Uneak\AssetsBundle\LibraryType\LibraryTypeInterface;
	use Uneak\AssetsBundle\Pool\Pool;

	interface AssetTypeInterface extends LibraryTypeInterface {

		public function buildPool(Pool $pool, $id, $data, array $sections = array());
		public function getRenderData($data);
		public function render(\Twig_Environment $twig, $data);
		public function getTypeGuesser();
		public function getAlias();

	}
