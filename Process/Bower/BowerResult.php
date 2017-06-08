<?php


	namespace Uneak\AssetsBundle\Process\Bower;

	use Symfony\Component\Process\Process;
	use Uneak\AssetsBundle\Bower\BowerPackage;

	class BowerResult {
		/**
		 * @var Process
		 */
		protected $process;
		/**
		 * @var array
		 */
		private $commands;
		/**
		 * @var BowerPackage
		 */
		private $bowerPackage;


		public function __construct(Process $process, BowerPackage $bowerPackage, array $commands) {
			$this->process = $process;
			$this->commands = $commands;
			$this->bowerPackage = $bowerPackage;
		}

		/**
		 * @return BowerPackage
		 */
		public function getBowerPackage() {
			return $this->bowerPackage;
		}

		/**
		 * @return Process
		 */
		public function getProcess() {
			return $this->process;
		}

		/**
		 * @return array
		 */
		public function getCommands() {
			return $this->commands;
		}


	}
