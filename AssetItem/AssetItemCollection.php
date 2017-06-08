<?php

	namespace Uneak\AssetsBundle\AssetItem;

	use Uneak\AssetsBundle\Exception\NotFoundException;
	use Symfony\Component\Config\Resource\ResourceInterface;

	class AssetItemCollection implements AssetItemCollectionInterface {

		/**
		 * @var AssetItemInterface[]
		 */
		protected $assetItems = array();
		/**
		 * @var \Symfony\Component\Config\Resource\ResourceInterface[]
		 */
		protected $resources = array();

		/**
		 * @param AssetItemInterface $item
		 *
		 * @return $this
		 */
		public function add(AssetItemInterface $item) {
			$this->assetItems[] = $item;
			return $this;
		}

		/**
		 * @param $name
		 * 
		 * @return AssetItemInterface[]
		 */
		public function findByName($name) {
			if ($name == null) {
				return $this->assetItems;
			}
			return array_filter($this->assetItems, function(AssetItem $v, $k) use ($name) {
				return $v->getParent() == '@'.$name;
			}, ARRAY_FILTER_USE_BOTH);
		}

		/**
		 * @param $type
		 *
		 * @return AssetItemInterface[]
		 */
		public function findByType($type) {
			return array_filter($this->assetItems, function(AssetItem $v, $k) use ($type) {
				return ($v instanceOf TypedAssetItemInterface && $v->getType() == $type);
			}, ARRAY_FILTER_USE_BOTH);
		}
		

		/**
		 * @return AssetItemInterface[]
		 */
		public function all() {
			return $this->assetItems;
		}
		
		
		

		/**
		 * @param $name
		 *
		 * @return bool
		 */
		public function has($name) {
			foreach ($this->assetItems as $object) {
				if ($object->getName() == $name) {
					return true;
				}
			}
			return false;
		}

		/**
		 * @param $name
		 *
		 * @return AssetItemInterface
		 * @throws \Uneak\AssetsBundle\Exception\NotFoundException
		 */
		public function get($name) {
			$found = null;
			foreach ($this->assetItems as $object) {
				if ($object->getName() == $name) {
					$found = $object;
					break;
				}
			}
			if (!$found) {
				throw new NotFoundException(sprintf('Not Found %s', $name));
			}

			return $found;
		}

		/**
		 * @param AssetItemInterface[] $items
		 *
		 * @return $this
		 */
		public function set(array $items) {
			foreach ($items as $item) {
				$this->add($item);
			}

			return $this;
		}

		/**
		 * @param $name
		 *
		 * @return $this
		 */
		public function remove($name) {
			foreach ((array)$name as $n) {
				foreach ($this->assetItems as &$object) {
					if ($object->getName() == $n) {
						unset($object);
					}
				}
			}

			return $this;
		}

		/**
		 * @param \Symfony\Component\Config\Resource\ResourceInterface $resource
		 *
		 * @return $this
		 */
		public function addResource(ResourceInterface $resource) {
			$this->resources[] = $resource;
			return $this;
		}

		/**
		 * @return \Symfony\Component\Config\Resource\ResourceInterface[]
		 */
		public function getResources() {
			return array_unique($this->resources);
		}

		/**
		 * @return \Symfony\Component\Config\Resource\ResourceInterface[]
		 */
		public function getAllResources() {
			$resources = $this->resources;
			foreach ($this->all() as $object) {
				$resources = array_merge($resources, $object->getResources());
			}
			return array_unique($resources);
		}

		/**
		 * @param mixed $mixed
		 *
		 * @return $this
		 */
		public function merge($mixed) {
			if ($mixed instanceof AssetItemCollectionInterface) {
				foreach ($mixed->all() as $object) {
					$this->add($object);
				}
				$this->resources = array_merge($this->resources, $mixed->getResources());
			}

			return $this;
		}


		public function __clone() {
			foreach ($this->assetItems as $item) {
				$this->assetItems[] = clone $item;
			}
		}


		/**
		 * {@inheritdoc}
		 */
		public function serialize() {
			return serialize(array(
				'objects' => $this->assetItems,
				'resources' => $this->resources,
			));
		}

		/**
		 * {@inheritdoc}
		 */
		public function unserialize($serialized) {
			$data = unserialize($serialized);
			$this->assetItems = $data['objects'];
			$this->resources = $data['resources'];
		}

	}