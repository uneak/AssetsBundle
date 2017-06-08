<?php

	namespace Uneak\AssetsBundle\Process\Npm;

	use Symfony\Component\EventDispatcher\EventDispatcherInterface;
	use Symfony\Component\Process\ProcessBuilder;
	use Uneak\AssetsBundle\Event\NpmCommandEvent;
	use Uneak\AssetsBundle\Event\NpmEvent;
	use Uneak\AssetsBundle\Event\NpmEvents;
	use Uneak\AssetsBundle\Event\NpmProcessEvent;
	use Uneak\AssetsBundle\Event\NpmResultEvent;
	use Uneak\AssetsBundle\Exception\CommandException;
	use Uneak\AssetsBundle\Npm\NpmPackageInterface;

	class NpmProcess {

		/**
		 * @var EventDispatcherInterface
		 */
		protected $eventDispatcher;
		/**
		 * @var string
		 */
		private $bin;


		public function __construct(EventDispatcherInterface $eventDispatcher, array $config) {
			$this->eventDispatcher = $eventDispatcher;
			$this->bin = $config['bin'];

		}

		/**
		 * @return string
		 */
		public function getBin() {
			return $this->bin;
		}

		public function install(NpmPackageInterface $npmPackage, $callback = null) {
			$event = new NpmEvent($npmPackage);
			$this->eventDispatcher->dispatch(NpmEvents::PRE_INSTALL, $event);
			$npmPackage = $event->getNpmPackage();

			$result = $this->process($npmPackage, array('install', '--json'), $callback);

			$event = new NpmResultEvent($result);
			$this->eventDispatcher->dispatch(NpmEvents::POST_INSTALL, $event);
			$result = $event->getNpmResult();

			return $result->getProcess()->getExitCode();
		}

		/**
		 * @param NpmPackageInterface $npmPackage
		 * @param array               $commands
		 * @param null                $callback
		 *
		 * @return NpmResult
		 */
		private function process(NpmPackageInterface $npmPackage, $commands, $callback = null) {

			$event = new NpmCommandEvent($npmPackage, $commands);
			$this->eventDispatcher->dispatch(NpmEvents::PRE_EXEC, $event);
			$npmPackage = $event->getNpmPackage();
			$commands = $event->getCommands();


			$processBuilder = new ProcessBuilder();
			$processBuilder->setWorkingDirectory($npmPackage->getPath());
			$processBuilder->setTimeout(600);
			$processBuilder->add($this->getBin());
			$processBuilder->add("--prefix \"" . $npmPackage->getModulesPath() . "\"");

			foreach ($commands as $command) {
				$processBuilder->add($command);
			}

			$process = $processBuilder->getProcess();
			$process->run($callback);

			if (!$process->isSuccessful()) {
				throw new CommandException($process->getCommandLine(), trim($process->getErrorOutput()));
			}

			$event = new NpmProcessEvent($npmPackage, $commands, $process);
			$this->eventDispatcher->dispatch(NpmEvents::POST_EXEC, $event);
			$commands = $event->getCommands();
			$npmPackage = $event->getNpmPackage();
			$process = $event->getProcess();

			return new NpmResult($process, $npmPackage, $commands);
		}


	}