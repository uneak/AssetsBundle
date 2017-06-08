<?php

	namespace Uneak\AssetsBundle\Event;

	use Symfony\Component\EventDispatcher\Event;
	use Uneak\AssetsBundle\Npm\NpmPackageInterface;

	class NpmEvent extends Event {

		/**
		 * @var NpmPackageInterface
		 */
		private $npmPackage;

		public function __construct(NpmPackageInterface $npmPackage) {
			$this->npmPackage = $npmPackage;
		}

		/**
		 * @return NpmPackageInterface
		 */
		public function getNpmPackage() {
			return $this->npmPackage;
		}
	}
