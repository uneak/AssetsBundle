<?php

	namespace Uneak\AssetsBundle\Javascript\JsItem;
	
	use Uneak\AssetsBundle\Javascript\Javascript;

	class JsInstance extends JsAbstract {
		CONST NEW = "new %s(%s)";
		CONST REQUIRE = "require('%s')(%s)";

		protected $declaration;
		protected $name;
		protected $parameters;


		public function __construct($name = null, $parameters = null, $declaration = self::NEW) {
			$this->name = $name;
			$this->parameters = $parameters;
			$this->declaration = $declaration;
		}


		protected function _getData() {
			return $this->name;
		}
		
		
		public function jsRender() {
			$name = ($this->name) ? $this->name : "";
			$parameters = ($this->parameters) ? Javascript::encode($this->parameters) : "";
			return sprintf($this->declaration, $name, $parameters);
		}
		
	}
