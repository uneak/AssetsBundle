<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;


	trait QueryTrait {

		private $queries = array();


		public function addQuery($key, $value) {
			if (!$this->hasQuery($key)) {
				$this->queries[$key] = $value;
			}
			return $this;
		}

		public function removeQuery($key) {
			if (isset($this->queries[$key])) {
				unset($this->queries[$key]);
			}
			return $this;
		}

		public function hasQuery($key) {
			return isset($this->queries[$key]);
		}

		/**
		 * @return array
		 */
		public function getQueries() {
			return $this->queries;
		}

		/**
		 * @param array $queries
		 *
		 * @return $this
		 */
		public function setQueries(array $queries) {
			foreach ($queries as $key => $value) {
				$this->addQuery($key, $value);
			}
			return $this;
		}



	}
