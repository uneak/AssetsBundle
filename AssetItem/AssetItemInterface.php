<?php

	namespace Uneak\AssetsBundle\AssetItem;

	use Symfony\Component\Config\Resource\ResourceInterface;

	interface AssetItemInterface extends MergeableInterface, \Serializable {
		/**
		 * @return string
		 */
		public function getName();
		/**
		 * @return string
		 */
		public function getId();
		/**
		 * @return string
		 */
		public function getParent();
		/**
		 * @param string $parent
		 */
		public function setParent($parent);
		/**
		 * @return \string[]
		 */
		public function getTags();
		/**
		 * @param \string[] $tags
		 */
		public function setTags(array $tags);
		/**
		 * @return string
		 */
		public function getInputDir();
		/**
		 * @param string $dir
		 */
		public function setInputDir($dir);
		/**
		 * @return string
		 */
		public function getOutputDir();
		/**
		 * @param string $dir
		 */
		public function setOutputDir($dir);
		/**
		 * @return string
		 */
		public function getPath();
		/**
		 * @param string $path
		 */
		public function setPath($path);
		/**
		 * @return array
		 */
		public function toArray();
		/**
		 * @return array
		 */
		public function getParameters();
		/**
		 * @param array $parameters
		 */
		public function setParameters($parameters);
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
	}
