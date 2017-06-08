<?php

	namespace Uneak\AssetsBundle\Javascript\JsItem;
	
	use Uneak\AssetsBundle\Webpack\Configuration\ExtraInterface;
	use Uneak\AssetsBundle\Javascript\Javascript;

	abstract class JsAbstract implements JsItemInterface {
		
		abstract protected function _getData();

		protected function _dump() {
			$data = $this->_getData();
			if (is_array($data)) {
				if ($this instanceof ExtraInterface) {
					$data = array_merge($this->getExtras(), $data);
				}
				return array_filter($data, function ($value) {
					return $this->_isNotEmpty($value);
				});
			} else {
				return ($this->_isNotEmpty($data)) ? $data : null;
			}
		}

		public function jsRender() {
			return Javascript::encode($this->_dump());
		}

		/**
		 * @return bool
		 */
		public function isEmpty() {
			return !$this->_isNotEmpty($this->_dump());
		}


		protected function _isNotEmpty($value) {
			return
				($value instanceof JsItemInterface && $value->isEmpty() === false) ||
				(is_string($value) && $value !== null) ||
				(is_array($value) && count($value));
		}

	}
