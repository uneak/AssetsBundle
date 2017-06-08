<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	use Uneak\AssetsBundle\Javascript\JsItem\JsAbstract;

	class Plugin extends JsAbstract implements ExtraInterface {

		use ExtraTrait;

		/**
		 * @var array
		 */
		private $plugins = array();

		/**
		 * @return array
		 */
		public function getPlugins() {
			return $this->plugins;
		}

		/**
		 * @param array $plugins
		 *
		 * @return $this
		 */
		public function setPlugins(array $plugins) {
			foreach ($plugins as $plugin) {
				$this->addPlugin($plugin);
			}

			return $this;
		}

		/**
		 * @param mixed $plugin
		 *
		 * @return $this
		 */
		public function addPlugin($plugin) {
			if (false === array_search($plugin, $this->plugins)) {
				$this->plugins[] = $plugin;
			}

			return $this;
		}

		public function removePlugin($plugin) {
			if (false !== $i = array_search($plugin, $this->plugins)) {
				unset($this->plugins[$i]);
			}

			return $this;
		}

		public function hasPlugin($plugin) {
			return (false !== array_search($plugin, $this->plugins));
		}

		protected function _getData() {
			return $this->getPlugins();
		}


	}
