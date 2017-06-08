<?php

	namespace Uneak\AssetsBundle\Event;

	use Symfony\Component\Process\Process;
	use Uneak\AssetsBundle\Npm\NpmPackageInterface;

	class NpmProcessEvent extends NpmCommandEvent {
		/**
		 * @var Process
		 */
		private $process;

		public function __construct(NpmPackageInterface $npmPackage, array $commands, Process $process) {
			parent::__construct($npmPackage, $commands);
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
