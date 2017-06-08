<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	class Webpack implements WebpackInterface {
		use ExtensionsTrait, ConfigurationsTrait, PropertiesTrait;

		/**
		 * @var string
		 */
		private $name;

		public function __construct($name, array $configurations = array(), array $extensions = array()) {
			$this->name = $name;
			$this->setConfigurations($configurations);
			$this->setExtensions($extensions);
		}

		public function getName() {
			return $this->name;
		}

	}
