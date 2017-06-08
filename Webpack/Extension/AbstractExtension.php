<?php

	namespace Uneak\AssetsBundle\Webpack\Extension;

	use Uneak\AssetsBundle\Finder\FinderExtensionInterface;
	use Uneak\AssetsBundle\Webpack\Configuration\PropertiesInterface;

	abstract class AbstractExtension implements ExtensionInterface {
		abstract public function build(PropertiesInterface $properties, FinderExtensionInterface $finder);

		public function getName() {
			$fqcn = get_class($this);
			if (preg_match('~([^\\\\]+?)(extension)?$~i', $fqcn, $matches)) {
				return strtolower(preg_replace(array('/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'), array('\\1_\\2', '\\1_\\2'), $matches[1]));
			}
		}
	}
