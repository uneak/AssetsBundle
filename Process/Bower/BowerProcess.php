<?php

	namespace Uneak\AssetsBundle\Process\Bower;

	use Symfony\Component\EventDispatcher\EventDispatcherInterface;
	use Symfony\Component\Process\ProcessBuilder;
	use Uneak\AssetsBundle\Bower\BowerPackage;
	use Uneak\AssetsBundle\Event\BowerCommandEvent;
	use Uneak\AssetsBundle\Event\BowerEvent;
	use Uneak\AssetsBundle\Event\BowerEvents;
	use Uneak\AssetsBundle\Event\BowerProcessEvent;
	use Uneak\AssetsBundle\Event\BowerResultEvent;
	use Uneak\AssetsBundle\Exception\CommandException;

	class BowerProcess {

		/**
		 * @var EventDispatcherInterface
		 */
		protected $eventDispatcher;
		/**
		 * @var string
		 */
		private $allowRoot;
		/**
		 * @var string
		 */
		private $bin;
		/**
		 * @var string
		 */
		private $registry;


		public function __construct(EventDispatcherInterface $eventDispatcher, array $config) {
			$this->eventDispatcher = $eventDispatcher;
			$this->allowRoot = $config['allow_root'];
			$this->bin = $config['bin'];
			$this->registry = $config['registry'];

		}

		/**
		 * @return string
		 */
		public function getAllowRoot() {
			return $this->allowRoot;
		}

		/**
		 * @return string
		 */
		public function getBin() {
			return $this->bin;
		}

		/**
		 * @return string
		 */
		public function getRegistry() {
			return $this->registry;
		}


		public function install(BowerPackage $bowerPackage, $callback = null) {
			$event = new BowerEvent($bowerPackage);
			$this->eventDispatcher->dispatch(BowerEvents::PRE_INSTALL, $event);
			$bowerPackage = $event->getBowerPackage();

			$result = $this->process($bowerPackage, array('install', '--json'), $callback);

			$event = new BowerResultEvent($result);
			$this->eventDispatcher->dispatch(BowerEvents::POST_INSTALL, $event);
			$result = $event->getBowerResult();

			return $result->getProcess()->getExitCode();
		}

		public function update(BowerPackage $bowerPackage, $callback = null) {
			$event = new BowerEvent($bowerPackage);
			$this->eventDispatcher->dispatch(BowerEvents::PRE_UPDATE, $event);
			$bowerPackage = $event->getBowerPackage();

			$result = $this->process($bowerPackage, array('update', '--json'), $callback);

			$event = new BowerResultEvent($result);
			$this->eventDispatcher->dispatch(BowerEvents::POST_UPDATE, $event);
			$result = $event->getBowerResult();

			return $result->getProcess()->getExitCode();
		}


		public function mapping(BowerPackage $bowerPackage, $callback = null) {
			$event = new BowerEvent($bowerPackage);
			$this->eventDispatcher->dispatch(BowerEvents::PRE_LIST, $event);
			$bowerPackage = $event->getBowerPackage();

			$result = $this->process($bowerPackage, array('list', '--json'), $callback);

			$event = new BowerResultEvent($result);
			$this->eventDispatcher->dispatch(BowerEvents::POST_LIST, $event);
			$result = $event->getBowerResult();

			return $result->getProcess()->getExitCode();
		}


		/**
		 * @param BowerPackage $bowerPackage
		 * @param array        $commands
		 * @param null         $callback
		 *
		 * @return BowerResult
		 */
		private function process(BowerPackage $bowerPackage, $commands, $callback = null) {

			$event = new BowerCommandEvent($bowerPackage, $commands);
			$this->eventDispatcher->dispatch(BowerEvents::PRE_EXEC, $event);
			$bowerPackage = $event->getBowerPackage();
			$commands = $event->getCommands();

			$processBuilder = new ProcessBuilder();
			$processBuilder->setWorkingDirectory($bowerPackage->getOutputDir());
			$processBuilder->setTimeout(600);
			$processBuilder->add($this->getBin());

			foreach ($commands as $command) {
				$processBuilder->add($command);
			}

			$process = $processBuilder->getProcess();
			$process->run($callback);

			if (!$process->isSuccessful()) {
				throw new CommandException($process->getCommandLine(), trim($process->getErrorOutput()));
			}

			$event = new BowerProcessEvent($bowerPackage, $commands, $process);
			$this->eventDispatcher->dispatch(BowerEvents::POST_EXEC, $event);
			$commands = $event->getCommands();
			$bowerPackage = $event->getBowerPackage();
			$process = $event->getProcess();

			return new BowerResult($process, $bowerPackage, $commands);
		}


	}