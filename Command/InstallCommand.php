<?php

	namespace Uneak\AssetsBundle\Command;

	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Filesystem\Exception\IOException;
	use Symfony\Component\Filesystem\Filesystem;
	use Symfony\Component\Finder\Finder;
	use Uneak\AssetsBundle\AssetItem\Bulk\BulkInterface;
	use Uneak\AssetsBundle\AssetItem\Library\InstallLibrary;
	use Uneak\AssetsBundle\Install\InstallBulk;


	class InstallCommand extends AbstractCommand {
		const METHOD_COPY = 'copy';
		const METHOD_ABSOLUTE_SYMLINK = 'absolute symlink';
		const METHOD_RELATIVE_SYMLINK = 'relative symlink';

		/**
		 * @var Filesystem
		 */
		protected $fs;

		protected function configure() {
			$this
				->setName('uneak:assets:install')
				->setDescription('Install one or all assets packages.');
		}

		protected function cmd(BulkInterface $bulk, InputInterface $input, OutputInterface $output) {
			$this->fs = $this->getContainer()->get('filesystem');
			$installBulk = new InstallBulk($bulk);


			$rows = array();
			$copyUsed = false;
			$exitCode = 0;


			foreach ($installBulk->getInstallPackages() as $installPackage) {


				/** @var $library InstallLibrary */
				foreach ($installPackage->getLibraries() as $library) {

					$inputDir = join(DIRECTORY_SEPARATOR,
						array_filter(array(rtrim($installPackage->getInputDir(), DIRECTORY_SEPARATOR), trim($library->getInputDir(), DIRECTORY_SEPARATOR)), function ($value) {
							return $value !== null && $value !== "";
						}));

					$outputDir = join(DIRECTORY_SEPARATOR,
						array_filter(array(rtrim($installPackage->getOutputDir(), DIRECTORY_SEPARATOR), trim($library->getOutputDir(), DIRECTORY_SEPARATOR)), function ($value) {
							return $value !== null && $value !== "";
						}));


					// Create the bundles directory otherwise symlink will fail.
					$this->fs->mkdir($outputDir, 0777);

					$this->io->newLine();

					if ($library->getMethod() == 'relative') {
						$expectedMethod = self::METHOD_RELATIVE_SYMLINK;
						$this->io->text(sprintf('Trying to install <comment>%s</comment> as <info>relative symbolic links</info>.', $library->getName()));
					} elseif ($library->getMethod() == 'symlink') {
						$expectedMethod = self::METHOD_ABSOLUTE_SYMLINK;
						$this->io->text(sprintf('Trying to install <comment>%s</comment> as <info>absolute symbolic links</info>.', $library->getName()));
					} else {
						$expectedMethod = self::METHOD_COPY;
						$this->io->text(sprintf('Installing <comment>%s</comment> as <info>hard copies</info>.', $library->getName()));
					}
					$this->io->text('From <info>' . $inputDir . '</info>.');
					$this->io->text('To <info>' . $outputDir . '</info>.');
					$this->io->newLine();

					if (!is_dir($inputDir)) {
						continue;
					}


					if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
						$message = sprintf("%s\n-> %s", $library->getName(), $outputDir);
					} else {
						$message = $library->getName();
					}


					try {
						$this->fs->remove($outputDir);

						if (self::METHOD_RELATIVE_SYMLINK === $expectedMethod) {
							$method = $this->relativeSymlinkWithFallback($inputDir, $outputDir);
						} elseif (self::METHOD_ABSOLUTE_SYMLINK === $expectedMethod) {
							$method = $this->absoluteSymlinkWithFallback($inputDir, $outputDir);
						} else {
							$method = $this->hardCopy($inputDir, $outputDir);
						}


						if (self::METHOD_COPY === $method) {
							$copyUsed = true;
						}

						if ($method === $expectedMethod) {
							$rows[] = array(sprintf('<fg=green;options=bold>%s</>', '\\' === DIRECTORY_SEPARATOR ? 'OK' : "\xE2\x9C\x94" /* HEAVY CHECK MARK (U+2714) */), $message, $method);
						} else {
							$rows[] = array(sprintf('<fg=yellow;options=bold>%s</>', '\\' === DIRECTORY_SEPARATOR ? 'WARNING' : '!'), $message, $method);
						}
					} catch (\Exception $e) {
						$exitCode = 1;
						$rows[] = array(sprintf('<fg=red;options=bold>%s</>', '\\' === DIRECTORY_SEPARATOR ? 'ERROR' : "\xE2\x9C\x98" /* HEAVY BALLOT X (U+2718) */), $message, $e->getMessage());
					}

				}


			}


			$this->io->table(array('', 'Library', 'Method / Error'), $rows);

			if (0 !== $exitCode) {
				$this->io->error('Some errors occurred while installing assets.');
			} else {
				if ($copyUsed) {
					$this->io->note('Some assets were installed via copy. If you make changes to these assets you have to run this command again.');
				}
				$this->io->success('All assets were successfully installed.');
			}


		}


		/**
		 * Try to create relative symlink.
		 *
		 * Falling back to absolute symlink and finally hard copy.
		 *
		 * @param string $originDir
		 * @param string $targetDir
		 *
		 * @return string
		 */
		private function relativeSymlinkWithFallback($originDir, $targetDir) {
			try {
				$this->symlink($originDir, $targetDir, true);
				$method = self::METHOD_RELATIVE_SYMLINK;
			} catch (IOException $e) {
				$method = $this->absoluteSymlinkWithFallback($originDir, $targetDir);
			}

			return $method;
		}

		/**
		 * Try to create absolute symlink.
		 *
		 * Falling back to hard copy.
		 *
		 * @param string $originDir
		 * @param string $targetDir
		 *
		 * @return string
		 */
		private function absoluteSymlinkWithFallback($originDir, $targetDir) {
			try {
				$this->symlink($originDir, $targetDir);
				$method = self::METHOD_ABSOLUTE_SYMLINK;
			} catch (IOException $e) {
				// fall back to copy
				$method = $this->hardCopy($originDir, $targetDir);
			}

			return $method;
		}

		/**
		 * Creates symbolic link.
		 *
		 * @param string $originDir
		 * @param string $targetDir
		 * @param bool   $relative
		 *
		 * @throws IOException If link can not be created.
		 */
		private function symlink($originDir, $targetDir, $relative = false) {
			if ($relative) {
				$originDir = $this->fs->makePathRelative($originDir, realpath(dirname($targetDir)));
			}
			$this->fs->symlink($originDir, $targetDir);
			if (!file_exists($targetDir)) {
				throw new IOException(sprintf('Symbolic link "%s" was created but appears to be broken.', $targetDir), 0, null, $targetDir);
			}
		}

		/**
		 * Copies origin to target.
		 *
		 * @param string $originDir
		 * @param string $targetDir
		 *
		 * @return string
		 */
		private function hardCopy($originDir, $targetDir) {
			$this->fs->mkdir($targetDir, 0777);
			// We use a custom iterator to ignore VCS files
			$this->fs->mirror($originDir, $targetDir, Finder::create()->ignoreDotFiles(false)->in($originDir));

			return self::METHOD_COPY;
		}


	}
