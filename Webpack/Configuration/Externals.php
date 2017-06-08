<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	use Uneak\AssetsBundle\Javascript\JsItem\JsAbstract;

	class Externals extends JsAbstract {

		private $externals;

		/**
		 * @return mixed
		 */
		public function getExternals() {
			return $this->externals;
		}

		/**
		 * @param mixed $externals
		 *
		 * @return $this
		 */
		public function setExternals($externals) {
			$this->externals = $externals;
			return $this;
		}


		protected function _getData() {
			return $this->getExternals();
		}
	}
