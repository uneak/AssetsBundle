<?php

	namespace Uneak\AssetsBundle\Event;

	use Uneak\AssetsBundle\Npm\NpmPackageInterface;

	class NpmCommandEvent extends NpmEvent {
		/**
		 * @var array
		 */
		protected $commands;


		public function __construct(NpmPackageInterface $npmPackage, array $commands) {
			parent::__construct($npmPackage);
			$this->commands = $commands;
		}

		/**
		 * @return array
		 */
		public function getCommands() {
			return $this->commands;
		}

		/**
		 * @param array $commands
		 */
		public function setCommands($commands) {
			$this->commands = $commands;
		}
		
	}
