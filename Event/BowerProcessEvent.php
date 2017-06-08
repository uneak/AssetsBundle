<?php

	namespace Uneak\AssetsBundle\Event;

	use Symfony\Component\Process\Process;
	use Uneak\AssetsBundle\AssetItem\Bulk\Bulk;
	use Uneak\AssetsBundle\Bower\BowerPackage;

	class BowerProcessEvent extends BowerCommandEvent {
		/**
		 * @var Process
		 */
		private $process;

		public function __construct(BowerPackage $bowerPackage, array $commands, Process $process) {
			parent::__construct($bowerPackage, $commands);
			$this->process = $process;
		}

		/**
		 * @param Process $process
		 */
		public function setProcess(Process $process) {
			$this->process = $process;
		}

		/**
		 * @return Process
		 */
		public function getProcess() {
			return $this->process;
		}
	}
