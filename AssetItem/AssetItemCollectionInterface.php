<?php

	namespace Uneak\AssetsBundle\AssetItem;
	
	use Symfony\Component\Config\Resource\ResourceInterface;

	interface AssetItemCollectionInterface extends MergeableInterface, \Serializable {
		/**
		 * @param AssetItemInterface $item
		 *
		 * @return $this
		 */
		public function add(AssetItemInterface $item);

		/**
		 * @param $name
		 *
		 * @return AssetItemInterface[]
		 */
		public function findByName($name);

		/**
		 * @param $type
		 *
		 * @return AssetItemInterface[]
		 */
		public function findByType($type);
			
		/**
		 * @return AssetItemInterface[]
		 */
		public function all();
			
		/**
		 * @param $name
		 *
		 * @return bool
		 */
		public function has($name);

		/**
		 * @param $name
		 *
		 * @return AssetItemInterface
		 * @throws \Uneak\AssetsBundle\Exception\NotFoundException
		 */
		public function get($name);

		/**
		 * @param AssetItemInterface[] $items
		 *
		 * @return $this
		 */
		public function set(array $items);

		/**
		 * @param $name
		 *
		 * @return $this
		 */
		public function remove($name);

		/**
		 * @return \Symfony\Component\Config\Resource\ResourceInterface[]
		 */
		public function getResources();
		/**
		 * @return \Symfony\Component\Config\Resource\ResourceInterface[]
		 */
		public function getAllResources();
		/**
		 * @param \Symfony\Component\Config\Resource\ResourceInterface $resource
		 *
		 * @return $this
		 */
		public function addResource(ResourceInterface $resource);
	}
