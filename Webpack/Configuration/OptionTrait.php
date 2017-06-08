<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;


	trait OptionTrait {

		private $options = array();


		public function addOption($key, $value) {
			if (!$this->hasOption($key)) {
				$this->options[$key] = $value;
			}
			return $this;
		}

		public function removeOption($key) {
			if (isset($this->options[$key])) {
				unset($this->options[$key]);
			}
			return $this;
		}

		public function hasOption($key) {
			return isset($this->options[$key]);
		}

		/**
		 * @return array
		 */
		public function getOptions() {
			return $this->options;
		}

		/**
		 * @param array $options
		 *
		 * @return $this
		 */
		public function setOptions(array $options) {
			foreach ($options as $key => $value) {
				$this->addOption($key, $value);
			}
			return $this;
		}



	}
