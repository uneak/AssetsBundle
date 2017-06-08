<?php

	namespace Uneak\AssetsBundle\Command;

	use Symfony\Component\Console\Helper\ProgressBar;
	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\Console\Input\InputInterface;
	use Uneak\AssetsBundle\AssetItem\Bulk\BulkInterface;
	use Uneak\AssetsBundle\Bower\BowerBulk;
	use Uneak\AssetsBundle\Exception\CommandException;
	use Uneak\AssetsBundle\Exception\RuntimeException;


	class BowerCommand extends AbstractCommand {


		protected $warn = array();

		protected $libraries = array();
		/**
		 * @var ProgressBar
		 */
		protected $progressBar;

		protected function configure() {
			$this
				->setName('uneak:assets:bower')
				->setDescription('Bower one or all assets packages.')
			;
		}

		protected function cmd(BulkInterface $bulk, InputInterface $input, OutputInterface $output) {
			$assetNamingStrategy = $this->getContainer()->get('uneak.assets.naming_strategy');
			$bower = $this->getContainer()->get('uneak.assets.bower.process');
			$filesystem = $this->getContainer()->get('filesystem');

			$bowerBulk = new BowerBulk($bulk, $assetNamingStrategy, $filesystem);

			$callback = function ($type, $data) use ($output, $input) {
				preg_match_all("/\\{(?:[^{}]|(?R))*\\}/", $data, $matches);
				if (isset($matches[0])) {
					foreach ($matches[0] as $item) {
						$data = json_decode($item, true);
						if (isset($data['level'])) {
							$this->process($data, $output, $input);
						}
					}
				}
			};

			ProgressBar::setFormatDefinition(
				'minimal',
				'  [%bar%] <info>%percent:3s%%</info> %endpoint% <comment>%message%</comment>'
			);


			$this->io->title('Bower install');
			foreach ($bowerBulk->getBowerPackages() as $name => $bowerPackage) {
				$this->io->newLine();
				$this->io->section($bowerPackage->getPackage()->getName());

				$this->io->text(sprintf('Trying to install packages into <info>"%s"</info>.', $bowerPackage->getOutputDir()));
				$this->io->newLine();

				$this->progressBar = $this->io->createProgressBar(100);
				$this->progressBar->setMessage('');
				$this->progressBar->setMessage('', 'endpoint');
				$this->progressBar->setFormat('minimal');
				$this->progressBar->start();
				try {
					$bower->update($bowerPackage, $callback);
				} catch (CommandException $ex) {
					$output->writeln($ex->getMessage());
					$output->writeln($ex->getCommandError());
					throw new RuntimeException("An error occured while installing packages");
				}
				$this->progressBar->finish();
				$this->io->newLine();
				$this->io->newLine();



				if (count($this->warn)) {
					$this->io->newLine();
					$this->io->note($this->warn);
					$this->warn = array();
					$this->libraries = array();
					$this->io->newLine();
				}


				$this->io->newLine();
				$this->io->text(sprintf('Trying to mapping packages.'));
				$this->io->newLine();

				$this->progressBar = $this->io->createProgressBar(100);
				$this->progressBar->setMessage('');
				$this->progressBar->setMessage('', 'endpoint');
				$this->progressBar->setFormat('minimal');
				$this->progressBar->start();
				try {
					$bower->mapping($bowerPackage, $callback);
				} catch (CommandException $ex) {
					$output->writeln($ex->getMessage());
					$output->writeln($ex->getCommandError());
					throw new RuntimeException("An error occured while mapping packages");
				}
				$this->progressBar->finish();
				$this->io->newLine();

				if (count($this->warn)) {
					$this->io->newLine();
					$this->io->note($this->warn);
					$this->warn = array();
					$this->libraries = array();
					$this->io->newLine();
				}


			}


			$this->io->newLine();
			$this->io->success('All assets were successfully installed.');

//			dump($this->libraries);
		}



		protected function process(array $data, OutputInterface $output, InputInterface $input) {

			$level = $data['level'];
			$id = $data['id'];
			$message = $data['message'];
			$data = $data['data'];


			$currentLibrary = null;
			if (isset($data['endpoint']['name'])) {
				$currentLibrary = $data['endpoint']['name'];
				$this->progressBar->setMessage($currentLibrary, 'endpoint');


				if (!isset($this->libraries[$currentLibrary])) {
					$this->libraries[$currentLibrary] = array(
						'progress' => array(),
						'cached' => '',
						'resolved' => '',
					);
				}


				if ($id == "progress") {
					preg_match('/^(.*):\s*(\d+)\%/', $message, $matches);

					if (!isset($this->libraries[$currentLibrary]['progress'][$matches[1]])) {
						$this->libraries[$currentLibrary]['progress'][$matches[1]] = array(
							'progress' => 0,
							'message' => ''
						);
					}

					$this->libraries[$currentLibrary]['progress'][$matches[1]]['progress'] = $matches[2];
					$this->libraries[$currentLibrary]['progress'][$matches[1]]['message'] = $message;


				} else if ($id == "resolved") {
					$this->libraries[$currentLibrary]['resolved'] = $message;

				} else if ($id == "cached") {
					$this->libraries[$currentLibrary]['cached'] = $message;
				}


			}

			$perc = 0;
			$cmpt = 0;
			foreach ($this->libraries as $lib) {
				foreach ($lib['progress'] as $plib) {
					$perc += $plib['progress'];
					$cmpt ++;
				}
			}
			if ($cmpt) {
				$perc = $perc / $cmpt;
			}

			$this->progressBar->setProgress($perc);
			$this->progressBar->setMessage($message);
			$this->progressBar->display();


			if ($level == "warn") {
				$this->warn[] = $message;
			}


			$this->lastLibrary = $currentLibrary;
		}

	}
