<?php

	namespace Uneak\AssetsBundle\Javascript\JsItem;
	
	use Uneak\AssetsBundle\Javascript\Javascript;

	class JsVar extends JsAbstract {
		
		CONST VAR = "var";
		CONST CONST = "const";
		CONST LET = "let";

		protected $value = null;
		protected $declaration;
		
		/**
		 * @var string
		 */
		protected $var;

		public function __construct($var, $value, $declaration = self::VAR) {
			$this->value = $value;
			$this->var = $var;
			$this->declaration = $declaration;
		}


		protected function _getData() {
			return $this->value;
		}
		
		
		public function jsRender() {
			$exp = sprintf("%s %s = ", $this->declaration, $this->var);
			$exp .= Javascript::encode($this->_dump());
			$exp .= ";";
			return $exp;
		}
		
	}
