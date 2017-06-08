<?php

	namespace Uneak\AssetsBundle\Command;

	use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Input\InputOption;
	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\Process\Process;


	class WebpackCommand extends ContainerAwareCommand {
		/**
		 * {@inheritdoc}
		 */
		protected function configure() {

			$this
				->setName('uneak:assets:webpack')
				->setDescription('Installs bundles react assets under a public web directory')
				->setDefinition(array(
					new InputOption('watch', 'w', InputOption::VALUE_NONE, 'Show assigned controllers in overview'),
					new InputOption('release', 'r', InputOption::VALUE_NONE, 'Show assigned controllers in overview'),
					new InputOption('analyze', 'a', InputOption::VALUE_NONE, 'Show assigned controllers in overview'),
					new InputOption('config', 'c', InputOption::VALUE_REQUIRED, 'The output format (txt, xml, json, or md)', null),
					new InputOption('script', 's', InputOption::VALUE_REQUIRED, 'The output format (txt, xml, json, or md)', "bundle"),
				));


		}


		/**
		 * {@inheritdoc}
		 */
		protected function execute(InputInterface $input, OutputInterface $output) {
			$webpackManager = $this->getContainer()->get('uneak.webpack.manager');

			$args = array();
			if ($input->getOption('watch')) {
				$args[] = "--watch";
			}
			if ($input->getOption('release')) {
				$args[] = "--release";
			}
			if ($input->getOption('analyze')) {
				$args[] = "--analyze";
			}
			if ($input->getOption('verbose')) {
				$args[] = "--verbose";
			}
			if ($input->getOption('script')) {
				$args[] = "--script";
				$args[] = $input->getOption('script');
			}
			if (!$input->getOption('config')) {
				throw new \Exception('faut un config');
			}

			//			$process = $webpackManager->createProcess("default", array("bundle"));
			$process = $webpackManager->createProcess($input->getOption('config'), $args);

			dump($process->getCommandLine());

			//			$process = $webpack->createProcess(array("--watch"));
			//			$process = $webpack->createProcess(array("bundle"));
			$this->runProcess($process, $output);

		}


		private function runProcess(Process $process, OutputInterface $output) {
			$process->run(function ($type, $buffer) use ($output) {
				$output->write($buffer);
			});
			if (!$process->isSuccessful()) {
				$error = <<<'ERROR'
            
<error>Error running %s (exit code %s)! Please look at the log for errors and re-run command.</error>
ERROR;
				$output->writeln(sprintf($error, $process->getCommandLine(), $process->getExitCode()));
			}
		}


	}
