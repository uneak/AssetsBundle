<?php

	namespace Uneak\AssetsBundle\Pool;

	use Uneak\AssetsBundle\AssetItem\MergeableInterface;

	class PoolItem implements PoolItemInterface {

		protected $id;
		/**
		 * @var array
		 */
		protected $dependencies;
		/**
		 * @var MergeableInterface
		 */
		protected $data;
		/**
		 * @var boolean
		 */
		protected $visited = false;


		public function __construct($id, array $dependencies = array(), MergeableInterface $data = null) {
			$this->id = $id;
			$this->data = $data;
			$this->dependencies = $dependencies;
		}

		/**
		 * @return mixed
		 */
		public function getId() {
			return $this->id;
		}


		/**
		 * @return array
		 */
		public function getDependencies() {
			return $this->dependencies;
		}

		/**
		 * @param array|string $dependencies
		 */
		public function setDependencies(array $dependencies = array()) {
			$this->dependencies = (array)$dependencies;
		}

		/**
		 * @return boolean
		 */
		public function isVisited() {
			return $this->visited;
		}

		/**
		 * @param boolean $visited
		 */
		public function setVisited(bool $visited) {
			$this->visited = $visited;
		}

		/**
		 * @return \Uneak\AssetsBundle\AssetItem\MergeableInterface
		 */
		public function getData() {
			return $this->data;
		}

		/**
		 * @param \Uneak\AssetsBundle\AssetItem\MergeableInterface $data
		 */
		public function setData(\Uneak\AssetsBundle\AssetItem\MergeableInterface $data) {
			$this->data = $data;
		}


		public function merge($mixed) {
			return $this;
		}
	}