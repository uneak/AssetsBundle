<?php

	namespace Uneak\AssetsBundle\Event;

	use Symfony\Component\EventDispatcher\Event;
	use Uneak\AssetsBundle\Bower\BowerPackage;

	class BowerEvent extends Event {

		/**
		 * @var BowerPackage
		 */
		private $bowerPackage;

		public function __construct(BowerPackage $bowerPackage) {
			$this->bowerPackage = $bowerPackage;
		}

		/**
		 * @return BowerPackage
		 */
		public function getBowerPackage() {
			return $this->bowerPackage;
		}
	}
