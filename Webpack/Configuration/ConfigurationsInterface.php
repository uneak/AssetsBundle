<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	interface ConfigurationsInterface {
		/**
		 * @param \Uneak\AssetsBundle\Webpack\Configuration\ConfigurationInterface $configuration
		 * @param int $priority
		 *
		 * @return $this
		 */
		public function addConfiguration(ConfigurationInterface $configuration, $priority = 0);
		/**
		 * @param $name
		 *
		 * @return $this
		 */
		public function removeConfiguration($name);
		/**
		 * @param $name
		 *
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Configuration
		 */
		public function getConfiguration($name);
		/**
		 * @param $name
		 *
		 * @return bool
		 */
		public function hasConfiguration($name);
		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Configuration[]
		 */
		public function getConfigurations();
		/**
		 * @param array $configurations
		 *
		 * @return $this
		 */
		public function setConfigurations(array $configurations);


	}
