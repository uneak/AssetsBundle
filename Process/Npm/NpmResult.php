<?php


	namespace Uneak\AssetsBundle\Process\Npm;

	use Symfony\Component\Process\Process;
	use Uneak\AssetsBundle\Npm\NpmPackageInterface;

	class NpmResult {
		/**
		 * @var Process
		 */
		protected $process;
		/**
		 * @var array
		 */
		private $commands;
		/**
		 * @var NpmPackageInterface
		 */
		private $npmPackage;


		public function __construct(Process $process, NpmPackageInterface $npmPackage, array $commands) {
			$this->process = $process;
			$this->commands = $commands;
			$this->npmPackage = $npmPackage;
		}

		/**
		 * @return NpmPackageInterface
		 */
		public function getNpmPackage() {
			return $this->npmPackage;
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
