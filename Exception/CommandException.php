<?php

	namespace Uneak\AssetsBundle\Exception;

	class CommandException extends RuntimeException implements ExceptionInterface {
		/**
		 * @var string
		 */
		private $commandError;

		/**
		 * @param string $commandName
		 * @param string $commandError
		 */
		public function __construct($commandName, $commandError) {
			$message = "Something went wrong while executing the command %s\n %s";
			$message = sprintf($message, $commandName, $commandError);
			parent::__construct($message);
			$this->commandError = $commandError;
		}

		/**
		 * @return string
		 */
		public function getCommandError() {
			return $this->commandError;
		}
	}
