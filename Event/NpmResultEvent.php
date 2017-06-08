<?php

	namespace Uneak\AssetsBundle\Event;

	use Symfony\Component\EventDispatcher\Event;
	use Uneak\AssetsBundle\Process\Npm\NpmResult;

	class NpmResultEvent extends Event {
		/**
		 * @var NpmResult
		 */
		private $npmResult;

		/**
		 * @param NpmResult $npmResult
		 */
		public function __construct(NpmResult $npmResult) {
			$this->npmResult = $npmResult;
		}

		/**
		 * @param NpmResult $npmResult
		 */
		public function setNpmResult(NpmResult $npmResult) {
			$this->npmResult = $npmResult;
		}

		/**
		 * @return NpmResult
		 */
		public function getNpmResult() {
			return $this->npmResult;
		}
	}
