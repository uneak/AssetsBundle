<?php

	namespace Uneak\AssetsBundle\Webpack\Extension\Resolver;

	use Uneak\AssetsBundle\Finder\FinderExtensionInterface;
	use Uneak\AssetsBundle\Webpack\Configuration\Configuration;
	use Uneak\AssetsBundle\Webpack\Configuration\PropertiesInterface;
	use Uneak\AssetsBundle\Webpack\Extension\AbstractExtension;

	class NpmFinderResolver extends AbstractExtension {

		public function build(PropertiesInterface $properties, FinderExtensionInterface $finder) {
			if (!$properties instanceof Configuration) {
				throw new \Exception("Configuration incompatible");
			}
			
			foreach ($finder->all() as $name => $path) {
				if (isset($path['path'])) {
					$properties->resolve()->alias()->addAlias($name, $path['path']);
				}
			}

		}

	}
