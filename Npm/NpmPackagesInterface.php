<?php

	namespace Uneak\AssetsBundle\Npm;

	use Symfony\Component\Config\Resource\ResourceInterface;

	interface NpmPackagesInterface extends \Serializable {
		/**
		 * @return \Symfony\Component\Config\Resource\ResourceInterface[]
		 */
		public function getResources();
		/**
		 * @param ResourceInterface $resource
		 * @return NpmPackagesInterface
		 */
		public function addResource(ResourceInterface $resource);
		/**
		 * @param string $alias
		 * @param NpmPackageInterface $package
		 * @return NpmPackageInterface
		 */
		public function addPackage($alias, NpmPackageInterface $package);
		/**
		 * @return $this
		 */
		public function removePackage($alias);
		/**
		 * @param string $alias
		 * @return $this
		 */
		public function getPackage($alias);
		/**
		 * @param string $alias
		 * @return bool
		 */
		public function hasPackage($alias);
		/**
		 * @return NpmPackageInterface[]
		 */
		public function getPackages();

	}
