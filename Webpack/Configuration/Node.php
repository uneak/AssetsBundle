<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	use Uneak\AssetsBundle\Javascript\JsItem\JsAbstract;

	class Node extends JsAbstract implements ExtraInterface {

		use ExtraTrait;
		
		private $console;
		private $process;
		private $global;
		private $__filename;
		private $__dirname;
		private $Buffer;
		private $setImmediate;

		/**
		 * @return mixed
		 */
		public function getConsole() {
			return $this->console;
		}

		/**
		 * @param mixed $console
		 */
		public function setConsole($console) {
			$this->console = $console;
		}

		/**
		 * @return mixed
		 */
		public function getProcess() {
			return $this->process;
		}

		/**
		 * @param mixed $process
		 */
		public function setProcess($process) {
			$this->process = $process;
		}

		/**
		 * @return mixed
		 */
		public function getGlobal() {
			return $this->global;
		}

		/**
		 * @param mixed $global
		 */
		public function setGlobal($global) {
			$this->global = $global;
		}

		/**
		 * @return mixed
		 */
		public function getFilename() {
			return $this->__filename;
		}

		/**
		 * @param mixed $_filename
		 */
		public function setFilename($_filename) {
			$this->__filename = $_filename;
		}

		/**
		 * @return mixed
		 */
		public function getDirname() {
			return $this->__dirname;
		}

		/**
		 * @param mixed $_dirname
		 */
		public function setDirname($_dirname) {
			$this->__dirname = $_dirname;
		}

		/**
		 * @return mixed
		 */
		public function getBuffer() {
			return $this->Buffer;
		}

		/**
		 * @param mixed $Buffer
		 */
		public function setBuffer($Buffer) {
			$this->Buffer = $Buffer;
		}

		/**
		 * @return mixed
		 */
		public function getSetImmediate() {
			return $this->setImmediate;
		}

		/**
		 * @param mixed $setImmediate
		 */
		public function setSetImmediate($setImmediate) {
			$this->setImmediate = $setImmediate;
		}

		protected function _getData() {
			return array(
				'console'      => $this->getConsole(),
				'process'      => $this->getProcess(),
				'global'       => $this->getGlobal(),
				'__filename'   => $this->getFilename(),
				'__dirname'    => $this->getDirname(),
				'Buffer'       => $this->getBuffer(),
				'setImmediate' => $this->getSetImmediate(),

			);
		}

		
	}
