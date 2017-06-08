<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;


	use Uneak\AssetsBundle\Javascript\Javascript;
	use Uneak\AssetsBundle\Javascript\JsItem\JsAbstract;

	class Requires extends JsAbstract {

		/**
		 * @var array
		 */
		private $requires = array();

		public function addRequire($var, $require) {
			$this->requires[$var] = $require;
			return $this;
		}

		public function removeRequire($var) {
			if (isset($this->requires[$var])) {
				unset($this->requires[$var]);
			}
			return $this;
		}

		/**
		 * @return array
		 */
		public function getRequires() {
			return $this->requires;
		}

		/**
		 * @param array $requires
		 *
		 * @return $this
		 */
		public function setRequires(array $requires) {
			foreach ($requires as $var => $require) {
				$this->addRequire($var, $require);
			}
			return $this;
		}


		protected function _getData() {
			return $this->getRequires();
		}

		public function jsRender() {
			$lines = array();
			foreach ($this->_dump() as $varName => $require) {
				$exp = "var " . $varName . " = ";
				$exp .= sprintf("require('%s');", $require);
				$lines[] = $exp;
			}
			return implode(PHP_EOL, $lines);
		}

	}
