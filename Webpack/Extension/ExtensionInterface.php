<?php

	namespace Uneak\AssetsBundle\Webpack\Extension;

	use Uneak\AssetsBundle\Finder\FinderExtensionInterface;
	use Uneak\AssetsBundle\Webpack\Configuration\PropertiesInterface;

	interface ExtensionInterface {
		public function build(PropertiesInterface $properties, FinderExtensionInterface $finder);
		public function getName();
	}
