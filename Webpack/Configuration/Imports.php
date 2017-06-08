<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	use Uneak\AssetsBundle\Javascript\JsItem\JsAbstract;

	class Imports extends JsAbstract {

		/**
		 * @var array
		 */
		private $imports = array();

		public function addImport($var, $from) {
			$varName = (is_array($var)) ? implode(", ", $var) : $var;
			$this->imports[$varName] = array($var, $from);
			return $this;
		}

		public function removeImport($var) {
			$varName = (is_array($var)) ? implode(", ", $var) : $var;
			if (isset($this->imports[$varName])) {
				unset($this->imports[$varName]);
			}
			return $this;
		}

		/**
		 * @return array
		 */
		public function getImports() {
			return $this->imports;
		}


		public function hasImport($var) {
			return (isset($this->imports[$var]));
		}


		/**
		 * @param array $imports
		 *
		 * @return $this
		 */
		public function setImports(array $imports) {
			foreach ($imports as $import) {
				$this->addImport($import[0], $import[1]);
			}
			return $this;
		}
		
		protected function _getData() {
			return $this->getImports();
		}
		
		public function jsRender() {
			$lines = array();
			foreach ($this->_dump() as $varName => $import) {
				list($var, $from) = $import;
				$exp = "import ";
				$exp .= (is_array($var)) ? sprintf("{ %s }", implode(", ", $var)) : $var;
				$exp .= sprintf(" from '%s';", $from);
				$lines[] = $exp;
			}
			return implode(PHP_EOL, $lines);
		}
		
	}
