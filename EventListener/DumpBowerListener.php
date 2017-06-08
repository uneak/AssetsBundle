<?php

	namespace Uneak\AssetsBundle\EventListener;

	use Symfony\Component\Filesystem\Filesystem;
	use Uneak\AssetsBundle\Event\BowerEvent;
	use Uneak\AssetsBundle\Event\BowerResultEvent;
	use Uneak\AssetsBundle\Exception\InvalidMappingException;
	use Uneak\AssetsBundle\Process\Bower\BowerProcess;

	class DumpBowerListener {

		/**
		 * @var \Symfony\Component\Filesystem\Filesystem
		 */
		private $fs;
		/**
		 * @var BowerProcess
		 */
		private $bowerProcess;

		public function __construct(Filesystem $fs, BowerProcess $bowerProcess) {
			$this->fs = $fs;
			$this->bowerProcess = $bowerProcess;
		}


		public function dumpBower(BowerEvent $event) {
			$bowerPackage = $event->getBowerPackage();
			if (!$this->fs->exists($bowerPackage->getOutputDir())) {
				$this->fs->mkdir($bowerPackage->getOutputDir(), 0777);
			}

			$this->dumpFile($bowerPackage->getOutputDir(), '.bowerrc', $this->getPackageBowerrc($bowerPackage->getOutputDir()));
			$this->dumpFile($bowerPackage->getOutputDir(), 'bower.json', $bowerPackage->getPackageBower());
		}

		
		public function dumpMapping(BowerResultEvent $event) {
			$bowerResult = $event->getBowerResult();
			$bowerPackage = $bowerResult->getBowerPackage();
			$output = $bowerResult->getProcess()->getOutput();

			$packageMapping = json_decode($output, true);
			if (null === $packageMapping) {
				throw new InvalidMappingException('Bower returned an invalid dependency mapping. This mostly happens when the dependencies are not yet installed or if you are using an old version of bower.');
			}

			$packageMapping['hash'] = $bowerPackage->getHash();

			if (!$this->fs->exists($bowerPackage->getOutputDir())) {
				$this->fs->mkdir($bowerPackage->getOutputDir(), 0777);
			}
			$this->dumpFile($bowerPackage->getOutputDir(), '.mapping', serialize($packageMapping));

		}


		private function dumpFile($dir, $file, $string) {
			$file = $dir . DIRECTORY_SEPARATOR . $file;
			if (!$this->fs->exists($file) || file_get_contents($file) != $string) {
				$this->fs->dumpFile($file, $string);
			}

			return $file;
		}


		private function getPackageBowerrc($outputDir) {
			$bowerrc = array_filter(array(
				'directory'  => $outputDir,
				'registry'   => $this->bowerProcess->getRegistry(),
				'allow_root' => $this->bowerProcess->getAllowRoot(),

			), function ($value) {
				return $value !== null;
			});

			return json_encode($bowerrc, JSON_FORCE_OBJECT);
		}

	}