<?php

	namespace Uneak\AssetsBundle\Event;

	use Uneak\AssetsBundle\Bower\BowerPackage;

	class BowerCommandEvent extends BowerEvent {
		/**
		 * @var array
		 */
		protected $commands;


		public function __construct(BowerPackage $bowerPackage, array $commands) {
			parent::__construct($bowerPackage);
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
