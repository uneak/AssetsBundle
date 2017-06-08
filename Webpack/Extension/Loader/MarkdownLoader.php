<?php

	namespace Uneak\AssetsBundle\Webpack\Extension\Loader;

	use Uneak\AssetsBundle\Finder\FinderExtensionInterface;
	use Uneak\AssetsBundle\Webpack\Configuration\Configuration;
	use Uneak\AssetsBundle\Webpack\Configuration\ModuleRulesRule;
	use Uneak\AssetsBundle\Webpack\Configuration\PropertiesInterface;
	use Uneak\AssetsBundle\Webpack\Configuration\UseEntry;
	use Uneak\AssetsBundle\Webpack\Extension\AbstractExtension;
	use Uneak\AssetsBundle\Javascript\JsItem\JsRegEx;

	class MarkdownLoader extends AbstractExtension {

		public function build(PropertiesInterface $properties, FinderExtensionInterface $finder) {
			if (!$properties instanceof Configuration) {
				throw new \Exception("Configuration incompatible");
			}

			$markdownRules = new ModuleRulesRule();
			$markdownRules->addTest(new JsRegEx("\.md$"));
			$markdownRules->addUse(new UseEntry($finder->path("@UneakAssetsBundle:node/lib/markdown-loader.js")));

			$properties->module()->rules()->addRule($markdownRules);

		}

	}
