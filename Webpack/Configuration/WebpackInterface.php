<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	interface WebpackInterface extends ExtensionsInterface, ConfigurationsInterface, PropertiesInterface {
		/**
		 * @return string
		 */
		public function getName();
	}
