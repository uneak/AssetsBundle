<?php

	namespace Uneak\AssetsBundle\Javascript\JsItem;
	
	use Uneak\AssetsBundle\Javascript\Javascript;

	class JsExport extends JsAbstract {
		
		/**
		 * @var string
		 */
		protected $var;
		protected $value;

		public function __construct($var, $value) {
			$this->var = $var;
			$this->value = $value;
		}

		protected function _getData() {
			return $this->value;
		}

		public function jsRender() {
			$var = (is_array($this->var)) ? sprintf("{ %s }", implode(", ", $this->var)) : $this->var;
			return sprintf("export %s %s", $var, Javascript::encode($this->_dump()));
		}
		
	}
