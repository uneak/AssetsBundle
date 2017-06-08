<?php

	namespace Uneak\AssetsBundle\Npm;

	use Symfony\Component\Config\Resource\ResourceInterface;

	class NpmPackages implements NpmPackagesInterface {
		/**
		 * @var NpmPackageInterface[]
		 */
		public $packages = array();

		/**
		 * @var \Symfony\Component\Config\Resource\ResourceInterface[]
		 */
		protected $resources = array();

		/**
		 * {@inheritdoc}
		 */
		public function getResources() {
			return array_unique($this->resources);
		}

		/**
		 * {@inheritdoc}
		 */
		public function addResource(ResourceInterface $resource) {
			$this->resources[] = $resource;

			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function addPackage($alias, NpmPackageInterface $package) {
			$this->packages[$alias] = $package;
			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function removePackage($alias) {
			unset($this->packages[$alias]);
			return $this;
		}

		/**
		 * {@inheritdoc}
		 */
		public function getPackage($alias) {
			return $this->packages[$alias];
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasPackage($alias) {
			return isset($this->packages[$alias]);
		}

		/**
		 * {@inheritdoc}
		 */
		public function getPackages() {
			return $this->packages;
		}


		/**
		 * {@inheritdoc}
		 */
		public function serialize() {
			return serialize(array(
				$this->packages,
				$this->resources,
			));
		}

		/**
		 * {@inheritdoc}
		 */
		public function unserialize($serialized) {
			list(
				$this->packages,
				$this->resources,
				) = unserialize($serialized);
		}

	}
