<?php

	namespace Uneak\AssetsBundle\Command;

	use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\Console\Style\OutputStyle;
	use Symfony\Component\Console\Style\SymfonyStyle;
	use Symfony\Component\Process\Process;
	use Uneak\AssetsBundle\Exception\CommandException;
	use Uneak\AssetsBundle\Exception\RuntimeException;
	use Uneak\NpmBundle\Package\Npm;


	class NpmCommand extends ContainerAwareCommand {

		/**
		 * @var OutputStyle
		 */
		protected $io;

		/**
		 * {@inheritdoc}
		 */
		protected function configure() {
			$this
				->setName('uneak:assets:npm')
				->setDescription('Installs npm modules');
		}

		/**
		 * @inheritdoc
		 */
		protected function execute(InputInterface $input, OutputInterface $output) {
			$npm = $this->getContainer()->get('uneak.assets.npm.process');
			$packages = $this->getContainer()->get('uneak.assets.npm')->getPackages();
			$this->io = new SymfonyStyle($input, $output);
			

			$callback = function ($type, $data) use ($output, $input) {
				dump($data);
			};

			foreach ($packages->getPackages() as $name => $npmPackage) {
				$this->io->newLine();
				$this->io->section($npmPackage->getPath());

				$this->io->text(sprintf('Trying to install packages.'));
				$this->io->newLine();

				try {
					$npm->install($npmPackage, $callback);
				} catch (CommandException $ex) {
					$output->writeln($ex->getMessage());
					$output->writeln($ex->getCommandError());
					throw new RuntimeException("An error occured while installing packages");
				}
				$this->io->newLine();

			}

		}

	}
