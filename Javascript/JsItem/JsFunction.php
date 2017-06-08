<?php

	namespace Uneak\AssetsBundle\Javascript\JsItem;
	
	use Uneak\AssetsBundle\Javascript\Javascript;

	class JsFunction extends JsAbstract {
		
		protected $name;
		protected $parameters;
		protected $content;


		public function __construct($name = null, $parameters = null, $content = null) {
			$this->name = $name;
			$this->parameters = $parameters;
			$this->content = $content;
		}


		protected function _getData() {
			return $this->content;
		}
		
		
		public function jsRender() {
			$exp = "";
			if ($this->name) {
				$exp .= $this->name;
			}
			$exp .= "(";
			if ($this->parameters) {
				$exp .= $this->parameters;
			}
			$exp .= ") ";

			if ($this->content instanceof JsList) {
				$exp .= "{".PHP_EOL;
				$exp .= Javascript::encode($this->content);
				$exp .= PHP_EOL."}";

			} else {
				if ($this->content) {
					$exp .= "=> ";
					$exp .= Javascript::encode($this->content);
				}

			}

			return $exp;
		}
		
	}
