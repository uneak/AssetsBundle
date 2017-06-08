<?php

	namespace Uneak\AssetsBundle\AssetItem\Asset;

	class WebpackAsset extends ExternalAsset {

		protected $input;
		protected $config;
		
		/**
		 * @return mixed
		 */
		public function getInput() {
			return $this->input;
		}

		/**
		 * @param mixed $input
		 *
		 * @return WebpackAsset
		 */
		public function setInput($input) {
			$this->input = $input;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getOutput() {
			return $this->getFile();
		}

		/**
		 * @param mixed $output
		 *
		 * @return WebpackAsset
		 */
		public function setOutput($output) {
			$this->setFile($output);
			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getConfig() {
			return $this->config;
		}

		/**
		 * @param mixed $config
		 *
		 * @return WebpackAsset
		 */
		public function setConfig($config) {
			$this->config = $config;

			return $this;
		}


		/**
		 * @param mixed $mixed
		 *
		 * @return array
		 */
		public function merge($mixed) {
			$mixed = parent::merge($mixed);

			if (isset($mixed['input'])) {
				$this->setInput($mixed['input']);
			}
			if (isset($mixed['output'])) {
				$this->setOutput($mixed['output']);
			}
			if (isset($mixed['config'])) {
				$this->setConfig($mixed['config']);
			}

			return $mixed;
		}

		/**
		 * @return array
		 */
		public function toArray() {
			$data = parent::toArray();
			$data['input'] = $this->getInput();
			$data['output'] = $this->getOutput();
			$data['config'] = $this->getConfig();
			return $data;
		}

		/**
		 * {@inheritdoc}
		 */
		public function unserialize($serialized) {
			$data = parent::unserialize($serialized);
			$this->setInput($data['input']);
			$this->setFile($data['output']);
			$this->setConfig($data['config']);
			return $data;
		}
		

	}