<?php

	namespace Uneak\AssetsBundle\Command;

	use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Style\OutputStyle;
	use Symfony\Component\Console\Style\SymfonyStyle;
	use Uneak\AssetsBundle\AssetItem\Bulk\BulkInterface;
	use Uneak\AssetsBundle\AssetItem\Package\Package;
	use Uneak\AssetsBundle\AssetItem\Bulk\Bulk;

	abstract class AbstractCommand extends ContainerAwareCommand {

		/**
		 * @var OutputStyle
		 */
		protected $io;

		protected function execute(InputInterface $input, OutputInterface $output) {
			$assetLoader = $this->getContainer()->get('uneak.assets.loader');
			$prefixConfig = $this->getContainer()->getParameter('uneak.assets.prefix_config');
			$packagesConfig = $this->getContainer()->getParameter('uneak.assets.packages_config');
			$this->io = new SymfonyStyle($input, $output);
			
			$bulk = new Bulk("bulk", $prefixConfig);

			foreach ($packagesConfig as $packageKey => $packageData) {
				$bulk->packages()->add(new Package($packageKey, '_bulk', $packageData));

				$subBulk = $assetLoader->load($packageData['resource'], 'bulk', array('parent' => '@' . $packageKey, 'tags' => array()));
				$bulk->merge($subBulk);
			}
			
			$this->cmd($bulk, $input, $output);
		}

		abstract protected function cmd(BulkInterface $bulk, InputInterface $input, OutputInterface $output);
	}
