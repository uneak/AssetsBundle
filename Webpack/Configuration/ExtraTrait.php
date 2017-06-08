<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;


	trait ExtraTrait {
		/**
		 * @var array
		 */
		protected $extras = array();

		/**
		 * @return array
		 */
		public function getExtras() {
			return $this->extras;
		}

		/**
		 * @param array $extras
		 *
		 * @return $this
		 */
		public function setExtras(array $extras) {
			foreach ($extras as $key => $extra) {
				$this->addExtra($key, $extra);
			}
			return $this;
		}

		/**
		 * @param string $key
		 * @param string $extra
		 *
		 * @return $this
		 */
		public function addExtra($key, $extra) {
			if ($key === null) {
				$this->extras[] = $extra;
			} else {
				$this->extras[$key] = $extra;
			}
			return $this;
		}

		public function removeExtra($key) {
			if (isset($this->extras[$key])) {
				unset($this->extras[$key]);
			}
			return $this;
		}

		public function hasExtra($key) {
			return (isset($this->extras[$key]));
		}

	}
