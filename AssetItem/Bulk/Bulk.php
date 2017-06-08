<?php

	namespace Uneak\AssetsBundle\AssetItem\Bulk;

	use Uneak\AssetsBundle\AssetItem\Asset\Asset;
	use Uneak\AssetsBundle\AssetItem\AssetItem;
	use Uneak\AssetsBundle\AssetItem\Asset\AssetCollection;
	use Uneak\AssetsBundle\AssetItem\AssetItemInterface;
	use Uneak\AssetsBundle\AssetItem\Library\Library;
	use Uneak\AssetsBundle\AssetItem\Library\LibraryCollection;
	use Uneak\AssetsBundle\AssetItem\Package\Package;
	use Uneak\AssetsBundle\AssetItem\Package\PackageCollection;
	use Uneak\AssetsBundle\Exception\NotFoundException;


	/**
	 * Class Bulk
	 *
	 * @package Uneak\AssetsBundle\Asset
	 */
	class Bulk extends AssetItem implements BulkInterface {
		/**
		 * @var PackageCollection
		 */
		protected $packages;
		/**
		 * @var LibraryCollection
		 */
		protected $libraries;
		/**
		 * @var AssetCollection
		 */
		protected $assets;
		/**
		 * @var AssetItemInterface
		 */
		protected $symlinks = array();


		public function __construct($name, array $options = array()) {
			parent::__construct($name, null, $options);

			$this->packages = new PackageCollection();
			$this->libraries = new LibraryCollection();
			$this->assets = new AssetCollection();
		}

		/**
		 * @return \Uneak\AssetsBundle\AssetItem\Package\PackageCollection
		 */
		public function packages() {
			return $this->packages;
		}

		/**
		 * @return \Uneak\AssetsBundle\AssetItem\Library\LibraryCollection
		 */
		public function libraries() {
			return $this->libraries;
		}

		/**
		 * @return \Uneak\AssetsBundle\AssetItem\Asset\AssetCollection
		 */
		public function assets() {
			return $this->assets;
		}

		/**
		 * @return \Symfony\Component\Config\Resource\ResourceInterface[]
		 */
		public function getAllResources() {
			$resources = $this->resources;
			$resources = array_merge($resources, $this->packages()->getAllResources());
			$resources = array_merge($resources, $this->libraries()->getAllResources());
			$resources = array_merge($resources, $this->assets()->getAllResources());
			return array_unique($resources);
		}
		
		
		/**
		 * @param string             $symlink
		 * @param AssetItemInterface $item
		 *
		 * @return $this
		 */
		public function addSymlink($symlink, AssetItemInterface $item) {
			$symlink = (array)$symlink;
			foreach ($symlink as $link) {
				$this->symlinks[$link] = $item;
			}

			return $this;
		}

		/**
		 * @return AssetItemInterface[]
		 */
		public function getSymlinks() {
			return $this->symlinks;
		}


		/**
		 * @param $symlink
		 *
		 * @return AssetItemInterface
		 */
		public function getSymlink($symlink) {
			return $this->symlinks[$symlink];
		}
		
		
		/**
		 * @param $symlink
		 *
		 * @return boolean
		 */
		public function hasSymlink($symlink) {
			return isset($this->symlinks[$symlink]);
		}








		public function find($link, AssetItemInterface $resolveItem = null) {
			if ($link instanceof AssetItem) {
				return $link;
			}

			try {
				$link = $this->_find($link);
			} catch (NotFoundException $e) {
				if (!$resolveItem) {
					throw new NotFoundException("symlink pas found ".$link);
				}

				$path = $this->unserializeLink($link);
				$try = array();
				$alternatives = array('package' => array(), 'tag' => array(), 'library' => array());

				try {
					while ($resolveItem) {
						$parentPath = $this->unserializeLink($resolveItem);
						if ($parentPath['package'] && !$path['package']) {
							$alternatives['package'][] = $parentPath['package'];
						}
						if (!$path['tag']) {
							foreach ($parentPath['tag'] as $tag) {
								$alternatives['tag'][] = $tag;
							}
						}
						if ($parentPath['library'] && !$path['library']) {
							$alternatives['library'][] = $parentPath['library'];
						}

						$resolveItem = $this->_find($resolveItem->getParent());
					}
				} catch (NotFoundException $e) {
				}

				$alternatives = array(
					'package' => array_unique($alternatives['package']),
					'tag'     => array_unique($alternatives['tag']),
					'library' => array_unique($alternatives['library'])
				);

				if (count($alternatives['library'])) {
					foreach ($alternatives['library'] as $library) {
						$libAnalyse = $path;
						$libAnalyse['library'] = $library;

						if (count($alternatives['tag'])) {
							foreach ($alternatives['tag'] as $tag) {
								$analyse = $libAnalyse;
								$analyse['tag'] = $tag;
								$try[] = $this->serializeLink($analyse);
							}
						}

						if (count($alternatives['package'])) {
							foreach ($alternatives['package'] as $package) {
								$analyse = $libAnalyse;
								$analyse['package'] = $package;
								$try[] = $this->serializeLink($analyse);
							}
						}


					}
				} else if (count($alternatives['package']) || count($alternatives['tag'])) {

					if (count($alternatives['tag'])) {
						foreach ($alternatives['tag'] as $tag) {
							$analyse = $path;
							$analyse['tag'] = $tag;
							$try[] = $this->serializeLink($analyse);
						}
					}

					if (count($alternatives['package'])) {
						foreach ($alternatives['package'] as $package) {
							$analyse = $path;
							$analyse['package'] = $package;
							$try[] = $this->serializeLink($analyse);
						}
					}

				}

				try {
					$link = $this->_find($try);
				} catch (NotFoundException $e) {
					throw new NotFoundException("symlink pas found ".$link);
				}

			}

			return $link;
		}


		private function _find($link) {
			$links = (array)$link;
			$link = array_shift($links);
			if ($this->hasSymlink($link)) {
				return $this->getSymlink($link);
			}

			if (!count($links)) {
				throw new NotFoundException();
			}

			return $this->_find($links);
		}

		private function unserializeLink($link) {

			$analyse = array('package' => '', 'tag' => array(), 'library' => '', 'asset' => '');

			if (is_string($link)) {
				preg_match("/^(@|#)?([^:]*)(?::|$)?([^:]*)?(?::|$)?([^:]*)?$/", $link, $matches);
				if ($matches[1] == "@") {
					$analyse['package'] = $matches[2];
				} else if ($matches[1] == "#") {
					$analyse['tag'][] = $matches[2];
				}
				$analyse['library'] = $matches[3];
				$analyse['asset'] = $matches[4];

			} else if ($link instanceof AssetItem) {

				if (!is_string($link->getParent()) || $link->getParent() != '_bulk') {
					$analyse = $this->unserializeLink($link->getParent());
				}

				if ($link instanceof Asset) {
					$analyse['asset'] = $link->getName();
				} else if ($link instanceof Library) {
					$analyse['library'] = $link->getName();
				} else if ($link instanceof Package) {
					$analyse['package'] = $link->getName();
				}

				foreach ($link->getTags() as $tag) {
					$analyse['tag'][] = $tag;
				}

			}

			return $analyse;
		}

		private function serializeLink(array $array) {
			$analyse = array();

			if ($array['package']) {
				$analyse[] = '@'.$array['package'];
			} else if ($array['tag']) {
				$analyse[] = '#'.$array['tag'];
			}
			if ($array['library'] || $array['asset']) {
				$analyse[] = $array['library'];
			}
			if ($array['asset']) {
				$analyse[] = $array['asset'];
			}

			return join(':', $analyse);
		}

		



		/**
		 * @param mixed $mixed
		 *
		 * @return array
		 */
		public function merge($mixed) {
			parent::merge($mixed);

			if ($mixed instanceof Bulk) {
				$this->packages()->merge($mixed->packages());
				$this->libraries()->merge($mixed->libraries());
				$this->assets()->merge($mixed->assets());
				$this->resources = array_merge($this->resources, $mixed->getResources());

			} else if ($mixed instanceof PackageCollection) {
				$this->packages()->merge($mixed);

			} else if ($mixed instanceof LibraryCollection) {
				$this->libraries()->merge($mixed);

			} else if ($mixed instanceof AssetCollection) {
				$this->assets()->merge($mixed);
			}

			return $mixed;
		}

		
		/**
		 * @return array
		 */
		public function toArray() {
			$data = parent::toArray();
			$data['packages'] = $this->packages();
			$data['libraries'] = $this->libraries();
			$data['assets'] = $this->assets();
			$data['symlinks'] = $this->getSymlinks();

			return $data;
		}

		/**
		 * {@inheritdoc}
		 */
		public function unserialize($serialized) {
			$data = parent::unserialize($serialized);
			$this->packages = $data['packages'];
			$this->libraries = $data['libraries'];
			$this->assets = $data['assets'];
			$this->symlinks = $data['symlinks'];

			return $data;
		}
		

	}