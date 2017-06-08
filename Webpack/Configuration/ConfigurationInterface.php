<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	interface ConfigurationInterface extends ExtensionsInterface, ConfigurationPropertiesInterface, ExtraInterface {
		public function getName();
	}
