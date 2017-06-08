<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	use Uneak\AssetsBundle\Webpack\Extension\ExtensionInterface;

	interface ExtensionsInterface {

		public function addExtension(ExtensionInterface $extension, $priority = 0);

		/**
		 * @param string $name
		 *
		 * @return ExtensionInterface
		 */
		public function getExtension($name);

		public function hasExtension($name);

		public function removeExtension($name);

		/**
		 * @return ExtensionInterface[]
		 */
		public function getExtensions();

		public function setExtensions(array $extensions);
	}
