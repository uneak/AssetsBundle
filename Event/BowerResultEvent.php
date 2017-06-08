<?php

	namespace Uneak\AssetsBundle\Event;

	use Symfony\Component\EventDispatcher\Event;
	use Uneak\AssetsBundle\Process\Bower\BowerResult;

	class BowerResultEvent extends Event {
		/**
		 * @var BowerResult
		 */
		private $bowerResult;

		/**
		 * @param BowerResult $bowerResult
		 */
		public function __construct(BowerResult $bowerResult) {
			$this->bowerResult = $bowerResult;
		}

		/**
		 * @param BowerResult $bowerResult
		 */
		public function setBowerResult(BowerResult $bowerResult) {
			$this->bowerResult = $bowerResult;
		}

		/**
		 * @return BowerResult
		 */
		public function getBowerResult() {
			return $this->bowerResult;
		}
	}
