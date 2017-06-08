<?php

	namespace Uneak\AssetsBundle\Javascript\JsItem;

	use Uneak\AssetsBundle\Javascript\Javascript;

	class JsArrayExtend extends JsAbstract {

		/**
		 * @var string
		 */
		protected $var;
		/**
		 * @var null
		 */
		private $trueValue;
		/**
		 * @var null
		 */
		private $falseValue;
		
		
		public function __construct($var, $trueValue = null, $falseValue = null) {
			$this->var = $var;
			$this->falseValue = $falseValue;
			$this->trueValue = $trueValue;
		}


		protected function _getData() {
			return $this->var;
		}
		
		public function jsRender() {
			$exp = sprintf("...%s", $this->var);
			if ($this->trueValue || $this->falseValue) {
				$exp .= " ? ";
				$exp .= Javascript::encode(($this->trueValue !== null) ? $this->trueValue : array());
				$exp .= " : ";
				$exp .= Javascript::encode(($this->falseValue !== null) ? $this->falseValue : array());
			}
			return $exp;
		}

	}
