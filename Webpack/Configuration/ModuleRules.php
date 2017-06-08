<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	use Uneak\AssetsBundle\Javascript\JsItem\JsAbstract;

	class ModuleRules extends JsAbstract implements ExtraInterface {
		
		use ExtraTrait;
		/**
		 * @var ModuleRulesRule[]
		 */
		private $rules = array();



		public function addRule(ModuleRulesRule $rule) {
			if ($i = array_search($rule, $this->rules) === false) {
				$this->rules[] = $rule;
			}			
			return $this;
		}

		public function removeRule(ModuleRulesRule $rule) {
			if ($i = array_search($rule, $this->rules) !== false) {
				unset($this->rules[$i]);
			}
			return $this;
		}

		public function hasRule(ModuleRulesRule $rule) {
			return ($i = array_search($rule, $this->rules) !== false);
		}

		/**
		 * @return array
		 */
		public function getRules() {
			return $this->rules;
		}

		/**
		 * @param ModuleRulesRule[] $rules
		 *
		 * @return $this
		 */
		public function setRules(array $rules) {
			foreach ($rules as $rule) {
				$this->addRule($rule);
			}
			return $this;
		}

		protected function _getData() {
			return $this->getRules();
		}
		

	}
