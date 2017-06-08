<?php

	namespace Uneak\AssetsBundle\AssetItem\Bulk;
	
	use Uneak\AssetsBundle\AssetItem\Asset\AssetCollection;
	use Uneak\AssetsBundle\AssetItem\AssetItemInterface;
	use Symfony\Component\Config\Resource\ResourceInterface;
	use Uneak\AssetsBundle\AssetItem\Library\LibraryCollection;
	use Uneak\AssetsBundle\AssetItem\Package\PackageCollection;

	interface BulkInterface extends AssetItemInterface {
		/**
		 * @return PackageCollection
		 */
		public function packages();
		/**
		 * @return LibraryCollection
		 */	
		public function libraries();
		/**
		 * @return AssetCollection
		 */
		public function assets();
		/**
		 * @return \Symfony\Component\Config\Resource\ResourceInterface[]
		 */
		public function getResources();
		/**
		 * @param \Symfony\Component\Config\Resource\ResourceInterface $resource
		 *
		 * @return $this
		 */
		public function addResource(ResourceInterface $resource);
		/**
		 * @param string             $symlink
		 * @param AssetItemInterface $item
		 *
		 * @return $this
		 */
		public function addSymlink($symlink, AssetItemInterface $item);
		/**
		 * @return array
		 */
		public function getSymlinks();
		/**
		 * @param $symlink
		 *
		 * @return AssetItemInterface
		 */
		public function getSymlink($symlink);
		/**
		 * @param $symlink
		 *
		 * @return boolean
		 */
		public function hasSymlink($symlink);


		/**
		 * @param                                                       $link
		 * @param \Uneak\AssetsBundle\AssetItem\AssetItemInterface|null $resolveItem
		 *
		 * @return AssetItemInterface
		 */
		public function find($link, AssetItemInterface $resolveItem = null);
	}
