<?php

	namespace Uneak\AssetsBundle\AssetItem\Library;

	class InstallLibrary extends Library {
		/**
		 * @var string
		 */
		private $method;

		/**
		 * @return string
		 */
		public function getMethod() {
			return $this->method;
		}

		/**
		 * @param string $method
		 */
		public function setMethod($method) {
			$this->method = $method;
		}

		/**
		 * @param mixed $mixed
		 *
		 * @return array
		 */
		public function merge($mixed) {
			$mixed = parent::merge($mixed);

			if (isset($mixed['method'])) {
				$this->setMethod($mixed['method']);
			}

			return $mixed;
		}

		/**
		 * @return array
		 */
		public function toArray() {
			$data = parent::toArray();
			$data['method'] = $this->getMethod();
			return $data;
		}

		/**
		 * {@inheritdoc}
		 */
		public function unserialize($serialized) {
			$data = parent::unserialize($serialized);
			$this->setMethod($data['method']);
			return $data;
		}

	}