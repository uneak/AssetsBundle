<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	use Uneak\AssetsBundle\Javascript\JsItem\JsAbstract;

	class Entry extends JsAbstract implements ExtraInterface {

		use ExtraTrait;

		/**
		 * @var array
		 */
		protected $entries = array();


		public function addEntry($name, $path) {
			if (!$this->hasEntry($name)) {
				$this->entries[$name] = $path;
			}
			return $this;
		}

		public function removeEntry($name) {
			if (isset($this->entries[$name])) {
				unset($this->entries[$name]);
			}
			return $this;
		}

		public function hasEntry($name) {
			return isset($this->entries[$name]);
		}

		/**
		 * @return array
		 */
		public function getEntries() {
			return $this->entries;
		}

		/**
		 * @param array $entries
		 *
		 * @return $this
		 */
		public function setEntries(array $entries) {
			foreach ($entries as $name => $path) {
				$this->addEntry($name, $path);
			}
			return $this;
		}

		public function _getData() {
			return $this->getEntries();
		}

		
	}
