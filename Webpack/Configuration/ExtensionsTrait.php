<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;


	use Uneak\AssetsBundle\Webpack\Extension\ExtensionInterface;

	trait ExtensionsTrait {
		/**
		 * @var array
		 */
		private $extensions = array();
		private $extSorted = false;

		public function addExtension(ExtensionInterface $extension, $priority = 0) {
			$this->extensions[$extension->getName()] = array($extension, $priority);
			$this->extSorted = false;
			return $this;
		}

		/**
		 * @return ExtensionInterface
		 */
		public function getExtension($name) {
			return $this->extensions[$name][0];
		}

		public function hasExtension($name) {
			return isset($this->extensions[$name]);
		}

		public function removeExtension($name) {
			unset($this->extensions[$name]);
			return $this;
		}

		public function setExtensions(array $extensions) {
			foreach ($extensions as $extension) {
				if (is_array($extension)) {
					$this->addExtension($extension[0], $extension[1]);
				} else if ($extension instanceof ExtensionInterface) {
					$this->addExtension($extension);
				} else {
					throw new \Exception('doit estre ExtensionInterface ou array(ExtensionInterface, priority)');
				}
			}
			return $this;
		}

		/**
		 * @return ExtensionInterface[]
		 */
		public function getExtensions() {
			if (!$this->extSorted) {
				usort($this->extensions, function ($a, $b) {
					if ($a[1] == $b[1]) return 0;
					return $a[1] < $b[1] ? 1 : -1;
				});
				$this->extSorted = true;
			}
			return array_column($this->extensions, 0);
		}

	}
