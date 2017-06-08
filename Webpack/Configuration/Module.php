<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	use Uneak\AssetsBundle\Javascript\JsItem\JsAbstract;

	class Module extends JsAbstract implements ExtraInterface {

		use ExtraTrait;
		
		private $noParse = null;
		private $rules;

		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\ModuleRules
		 */
		public function rules() {
			if (!$this->rules) {
				$this->rules = new ModuleRules();
			}
			return $this->rules;
		}


		/**
		 * @return null
		 */
		public function getNoParse() {
			return $this->noParse;
		}

		/**
		 * @param null $noParse
		 */
		public function setNoParse($noParse) {
			$this->noParse = $noParse;
		}

		protected function _getData() {
			return array(
				'noParse' => $this->getNoParse(),
				'rules'   => $this->rules,
			);
		}

	}
