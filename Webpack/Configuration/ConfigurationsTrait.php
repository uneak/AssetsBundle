<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	trait ConfigurationsTrait {

		/**
		 * @var Configuration[]
		 */
		private $configurations = array();
		private $confSorted = false;


		public function addConfiguration(ConfigurationInterface $configuration, $priority = 0) {
			$this->configurations[$configuration->getName()] = array($configuration, $priority);
			$this->confSorted = false;
			return $this;
		}

		/**
		 * @param string $name
		 * @return ConfigurationInterface
		 */
		public function getConfiguration($name) {
			return $this->configurations[$name][0];
		}

		public function hasConfiguration($name) {
			return isset($this->configurations[$name]);
		}

		public function removeConfiguration($name) {
			unset($this->configurations[$name]);
			return $this;
		}

		public function setConfigurations(array $configurations) {
			foreach ($configurations as $configuration) {
				if (is_array($configuration)) {
					$this->addConfiguration($configuration[0], $configuration[1]);
				} else if ($configuration instanceof ConfigurationInterface) {
					$this->addConfiguration($configuration);
				} else {
					throw new \Exception('doit estre ConfigurationInterface ou array(ConfigurationInterface, priority)');
				}
			}
			return $this;
		}

		/**
		 * @return ConfigurationInterface[]
		 */
		public function getConfigurations() {
			if (!$this->confSorted) {
				usort($this->configurations, function ($a, $b) {
					if ($a[1] == $b[1]) return 0;
					return $a[1] < $b[1] ? 1 : -1;
				});
				$this->confSorted = true;
			}
			return array_column($this->configurations, 0);
		}

	}
