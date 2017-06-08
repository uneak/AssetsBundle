<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;
	
	use Uneak\AssetsBundle\Javascript\JsItem\JsAbstract;

	class UseEntry extends JsAbstract implements ExtraInterface {
		use OptionTrait, ExtraTrait;

		private $loader;
		
		public function __construct($loader = null, array $options = null) {
			if ($loader) {
				$this->setLoader($loader);
			}
			if ($options) {
				$this->setOptions($options);
			}
		}

		/**
		 * @return string
		 */
		public function getLoader() {
			return $this->loader;
		}

		/**
		 * @param string $loader
		 *
		 * @return UseEntry
		 */
		public function setLoader($loader) {
			$this->loader = $loader;
			return $this;
		}

		
		protected function _getData() {
			return array(
				'loader'  => $this->getLoader(),
				'options' => $this->getOptions(),
			);
		}
		
		
	}
