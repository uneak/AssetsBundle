<?php

	namespace Uneak\AssetsBundle\Javascript\JsItem;

	class JsRegEx extends JsAbstract {
		protected $value = null;

		public function __construct($value) {
			$this->value = $value;
		}

		protected function _getData() {
			return $this->value;
		}

		public function jsRender() {
			return "/".$this->_dump()."/";
		}

	}
