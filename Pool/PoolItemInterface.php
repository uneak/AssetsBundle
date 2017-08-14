<?php

	namespace Uneak\AssetsBundle\Pool;

	use Uneak\AssetsBundle\AssetItem\MergeableInterface;

	interface PoolItemInterface extends MergeableInterface {

		/**
		 * @return string
		 */
		public function getId();
		/**
		 * @return MergeableInterface
		 */
		public function getData();
		public function setData(MergeableInterface $data);
		/**
		 * @return array
		 */
		public function getDependencies();
		public function setDependencies(array $dependencies = array());
		/**
		 * @return boolean
		 */
		public function isVisited();
		public function setVisited($visited);

	}
