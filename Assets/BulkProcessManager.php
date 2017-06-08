<?php

	namespace Uneak\AssetsBundle\Assets;


	use Uneak\AssetsBundle\AssetItem\Bulk\BulkInterface;
	use Uneak\AssetsBundle\Exception\BulkProcessNotFoundException;

	class BulkProcessManager implements BulkProcessInterface {

		/**
		 * @var BulkProcessInterface[]
		 */
		protected $bulkProcesses = array();
		private $sorted = false;


		public function __construct(array $bulkProcesses = array()) {
			$this->setBulkProcesses($bulkProcesses);
		}

		/**
		 * @return BulkProcessInterface[]
		 */
		public function getBulkProcesses() {
			if (!$this->sorted) {
				usort($this->bulkProcesses, function ($a, $b) {
					if ($a[1] == $b[1]) return 0;
					return $a[1] < $b[1] ? 1 : -1;
				});
				$this->sorted = true;
			}
			return array_column($this->bulkProcesses, 0);
		}

		/**
		 * @param $name
		 *
		 * @return \Uneak\AssetsBundle\Assets\BulkProcessInterface
		 * @throws \Uneak\AssetsBundle\Exception\BulkProcessNotFoundException
		 */
		public function getBulkProcess($name) {
			if (!isset($this->bulkProcesses[$name])) {
				throw new BulkProcessNotFoundException(sprintf("BulkProcess %s not found", $name));
			}

			return $this->bulkProcesses[$name][0];
		}

		/**
		 * @param BulkProcessInterface $bulkProcesses
		 * @param int $priority
		 * @param string $name
		 *
		 * @return $this
		 */
		public function addBulkProcess(BulkProcessInterface $bulkProcesses, $priority = 0, $name = null) {
			if ($name) {
				$this->bulkProcesses[$name] = array($bulkProcesses, $priority);

			} else {
				$this->bulkProcesses[] = array($bulkProcesses, $priority);
			}
			$this->sorted = false;
			return $this;
		}

		/**
		 * @param array $bulkProcesses
		 *
		 * @return $this
		 * @throws \Exception
		 */
		public function setBulkProcesses(array $bulkProcesses) {
			foreach ($bulkProcesses as $bulkProcess) {
				if (is_array($bulkProcess)) {
					$process = (isset($bulkProcess[0])) ? $bulkProcess[0] : null;
					$priority = (isset($bulkProcess[1])) ? $bulkProcess[1] : 0;
					$name = (isset($bulkProcess[2])) ? $bulkProcess[2] : null;

					$this->addBulkProcess($process, $priority, $name);
				} else if ($bulkProcess instanceof BulkProcessInterface) {
					$this->addBulkProcess($bulkProcess);
				} else {
					throw new \Exception('doit estre BulkProcessInterface ou array(BulkProcessInterface, priority)');
				}
			}
			return $this;
		}

		/**
		 * @param string $name
		 *
		 * @return bool
		 */
		public function hasBulkProcess($name) {
			return isset($this->bulkProcesses[$name]);
		}

		/**
		 * @param string $name
		 *
		 * @return $this
		 */
		public function removeBulkProcess($name) {
			unset($this->bulkProcesses[$name]);
			return $this;
		}


		public function process(BulkInterface $bulk) {
			foreach ($this->getBulkProcesses() as $bulkProcess) {
				$bulkProcess->process($bulk);
			}
		}

		public function check(BulkInterface $bulk, BulkInterface $processedBulk) {
			foreach ($this->getBulkProcesses() as $bulkProcess) {
				$bulkProcess->check($bulk, $processedBulk);
			}
		}
	}