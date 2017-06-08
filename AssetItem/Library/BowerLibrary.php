<?php

	namespace Uneak\AssetsBundle\AssetItem\Library;


	class BowerLibrary extends Library {
		/**
		 * @var array
		 */
		private $endpoint;


		public function getEndpoint($key = null) {
			return ($key) ? $this->endpoint[$key] : $this->endpoint;
		}

		public function setEndpoint(array $endpoint) {

			if (!$endpoint || count($endpoint) == 0) {
				return;
			}

			if (isset($endpoint['name'])) {
				$name = $endpoint['name'];
			} else if (isset($endpoint[0])) {
				$name = $endpoint[0];
			} else {
				// TODO: faire l'ecxeption
				throw new \Exception('endpoint invalide');
			}

			$source = (isset($endpoint['source'])) ? $endpoint['source'] : null;
			$target = (isset($endpoint['target'])) ? $endpoint['target'] : '*';

			if (!$source) {
				if (isset($endpoint[1])) {
					preg_match("/(?:(.*)?#)?(.*)/", $endpoint[1], $matches);
					if ($matches[1]) {
						$source = $matches[1];
					}
					$target = $matches[2];

				} else {
					$source = $name;

				}
			}

			$this->endpoint = array(
				'name'   => $name,
				'source' => $source,
				'target' => $target,
			);
		}
		
		


		/**
		 * @param mixed $mixed
		 *
		 * @return array
		 */
		public function merge($mixed) {
			$mixed = parent::merge($mixed);

			if (isset($mixed['endpoint'])) {
				$this->setEndpoint($mixed['endpoint']);
			}

			return $mixed;
		}

		/**
		 * @return array
		 */
		public function toArray() {
			$data = parent::toArray();
			$data['endpoint'] = $this->getEndpoint();
			return $data;
		}

		/**
		 * {@inheritdoc}
		 */
		public function unserialize($serialized) {
			$data = parent::unserialize($serialized);
			$this->setEndpoint($data['endpoint']);
			return $data;
		}
		
	}