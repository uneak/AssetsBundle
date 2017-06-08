<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	use Uneak\AssetsBundle\Javascript\JsItem\JsList;

	trait PropertiesTrait {
		/**
		 * @var JsList
		 */
		private $header;
		/**
		 * @var JsList
		 */
		private $footer;
		/**
		 * @var \Uneak\AssetsBundle\Webpack\Configuration\Requires
		 */
		private $requires;
		/**
		 * @var \Uneak\AssetsBundle\Webpack\Configuration\Imports
		 */
		private $imports;


		/**
		 * @return \Uneak\AssetsBundle\Javascript\JsItem\JsList
		 */
		public function header() {
			if (!$this->header) {
				$this->header = new JsList();
			}
			return $this->header;
		}

		/**
		 * @return \Uneak\AssetsBundle\Javascript\JsItem\JsList
		 */
		public function footer() {
			if (!$this->footer) {
				$this->footer = new JsList();
			}
			return $this->footer;
		}

		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Requires
		 */
		public function requires() {
			if (!$this->requires) {
				$this->requires = new Requires();
			}
			return $this->requires;
		}

		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Imports
		 */
		public function imports() {
			if (!$this->imports) {
				$this->imports = new Imports();
			}
			return $this->imports;
		}



	}
