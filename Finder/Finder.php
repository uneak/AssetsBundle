<?php

	namespace Uneak\AssetsBundle\Finder;
	
	use Uneak\AssetsBundle\Exception\LinkNotFoundException;

	class Finder implements FinderExtensionInterface {

		/**
		 * @var FinderExtensionInterface[]
		 */
		private $finderExtensions;
		/**
		 * @var bool
		 */
		private $sorted = false;
		
		public function __construct(array $finderExtensions = array()) {
			foreach ($finderExtensions as $finderExtension) {
				if (is_array($finderExtension)) {
					$extension = (isset($finderExtension[0])) ? $finderExtension[0] : null;
					$priority = (isset($finderExtension[1])) ? $finderExtension[1] : 0;

					if (!$extension) {
						throw new \Exception('doit estre FinderExtensionInterface ou array(FinderExtensionInterface, priority)');
					}

					$this->addFinderExtension($extension, $priority);
				} else if ($finderExtension instanceof FinderExtensionInterface) {
					$this->addFinderExtension($finderExtension);
				} else {
					throw new \Exception('doit estre FinderExtensionInterface ou array(FinderExtensionInterface, priority)');
				}
			}
		}


		public function path($key) {
			$founded = null;
			foreach ($this->getFinderExtensions() as $finderExtension) {
				try {
					$founded = $finderExtension->path($key);
					break;
				} catch (\Exception $e) {}
			}
			if (!$founded) {
				throw new LinkNotFoundException(sprintf("path %s not found", $key));
			}
			return $founded;
		}

		public function file($key) {
			$founded = null;
			foreach ($this->getFinderExtensions() as $finderExtension) {
				try {
					$founded = $finderExtension->file($key);
					break;
				} catch (\Exception $e) {}
			}
			if (!$founded) {
				throw new LinkNotFoundException(sprintf("path %s not found", $key));
			}
			return $founded;
		}


		/**
		 * @param $key
		 *
		 * @return string
		 * @throws \Uneak\AssetsBundle\Exception\LinkNotFoundException
		 */
		public function find($key) {
			$founded = null;
			foreach ($this->getFinderExtensions() as $finderExtension) {
				try {
					$founded = $finderExtension->find($key);
					break;
				} catch (\Exception $e) {}
			}
			
			if (!$founded) {
				throw new LinkNotFoundException(sprintf("lien %s not found", $key));
			}
			
			return $founded;
		}

		/**
		 * @return array
		 */
		public function all() {
			$all = array();
			foreach ($this->getFinderExtensions() as $finderExtension) {
				$all = array_merge($all, $finderExtension->all());
			}
			return $all;
		}


		/**
		 * @param $key
		 *
		 * @return bool
		 */
		public function has($key) {
			foreach ($this->getFinderExtensions() as $finderExtension) {
				if ($finderExtension->has($key)) {
					return true;
				}
			}
			return false;
		}
		

		/**
		 * @return FinderExtensionInterface[]
		 */
		private function getFinderExtensions() {
			if (!$this->sorted) {
				usort($this->finderExtensions, function ($a, $b) {
					if ($a[1] == $b[1]) return 0;
					return $a[1] < $b[1] ? 1 : -1;
				});
				$this->sorted = true;
			}
			return array_column($this->finderExtensions, 0);
			
		}

		/**
		 * @param FinderExtensionInterface $finderExtension
		 * @param int                      $priority
		 *
		 * @return $this
		 */
		private function addFinderExtension(FinderExtensionInterface $finderExtension, $priority = 0) {
			$this->finderExtensions[] = array($finderExtension, $priority);
			$this->sorted = false;
			return $this;
		}


	}