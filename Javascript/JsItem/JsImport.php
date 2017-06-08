<?php

	namespace Uneak\AssetsBundle\Javascript\JsItem;
	
	use Uneak\AssetsBundle\Javascript\Javascript;

	class JsImport extends JsAbstract {
		
		/**
		 * @var string
		 */
		protected $var;
		protected $from;

		public function __construct($var, $from) {
			$this->var = $var;
			$this->from = $from;
		}

		protected function _getData() {
			return $this->from;
		}

		public function jsRender() {
			$var = (is_array($this->var)) ? sprintf("{ %s }", implode(", ", $this->var)) : $this->var;
			return sprintf("import %s from %s", $var, Javascript::encode($this->_dump()));
		}
		
	}
