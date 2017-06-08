<?php

	namespace Uneak\AssetsBundle\Command;

	use Symfony\Component\Console\Input\InputArgument;
	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\Console\Input\InputInterface;
	use Uneak\AssetsBundle\AssetItem\Bulk\BulkInterface;


	class CheckCommand extends AbstractCommand {

		protected function configure() {
			$this
				->setName('uneak:assets:check')
				->setDescription('Check one or all assets packages.')
			;
		}

		protected function cmd(BulkInterface $bulk, InputInterface $input, OutputInterface $output) {


//			$packages = $this->bulk->all();
//			/** @var $package Package */
//			foreach ($packages as $package) {
//				$dir = $package->getCwd() . DIRECTORY_SEPARATOR . $package->getDirectory();
//
//				$libraries = $package->all();
//				/** @var $library Library */
//				foreach ($libraries as $libraryKey => $library) {
//					$dependencyDir = $dir . DIRECTORY_SEPARATOR . $library->getId();
//					$assets = $library->all();
//					/** @var $asset Asset */
//					foreach ($assets as $assetName => $asset) {
//						$assetPath = $dependencyDir . DIRECTORY_SEPARATOR . $asset->getPath();
//						if (!$this->filesystem->exists($assetPath)) {
//							$output->writeln(sprintf('<comment>"%s"</comment> : <error>"%s" Does not exist</error>', $libraryKey . '.' . $assetName, $assetPath));
//						}
//					}
//				}
//			}
			
		}

	}
