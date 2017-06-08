<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	use Uneak\AssetsBundle\Javascript\Javascript;
	use Uneak\AssetsBundle\Javascript\JsItem\JsAbstract;

	class ResolveAlias extends JsAbstract implements ExtraInterface {

		use ExtraTrait;

		/**
		 * @var array
		 */
		private $aliases = array();

		public function addAlias($name, $path) {
			$this->aliases[$name] = $path;
			return $this;
		}

		public function removeAlias($nameOrPath) {
			if (isset($this->aliases[$nameOrPath])) {
				unset($this->aliases[$nameOrPath]);
			} else if ($i = array_search($nameOrPath, $this->aliases) !== false) {
				unset($this->aliases[$i]);
			}

			return $this;
		}


		/**
		 * @return array
		 */
		public function getAliases() {
			return $this->aliases;
		}

		/**
		 * @param array $aliases
		 *
		 * @return $this
		 */
		public function setAliases(array $aliases) {
			foreach ($aliases as $name => $path) {
				$this->addAlias($name, $path);
			}
			return $this;
		}

		protected function _getData() {
			return $this->getAliases();
		}

		public function jsRender() {
			$originalAlias = $this->_dump();
			$alias = array();
			array_walk($originalAlias, function ($value, $key) use (&$alias) {
				$alias['"'.$key.'"'] = $value;
			});
			return Javascript::encode($alias);
		}
	}
