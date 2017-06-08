<?php

	namespace Uneak\AssetsBundle\Webpack\Extension\Loader;

	use Uneak\AssetsBundle\Finder\FinderExtensionInterface;
	use Uneak\AssetsBundle\Webpack\Configuration\Configuration;
	use Uneak\AssetsBundle\Webpack\Configuration\ModuleRulesRule;
	use Uneak\AssetsBundle\Webpack\Configuration\PropertiesInterface;
	use Uneak\AssetsBundle\Webpack\Configuration\UseEntry;
	use Uneak\AssetsBundle\Webpack\Extension\AbstractExtension;
	use Uneak\AssetsBundle\Javascript\JsItem\JsEval;
	use Uneak\AssetsBundle\Javascript\JsItem\JsRegEx;

	class UrlLoader extends AbstractExtension {

		public function build(PropertiesInterface $properties, FinderExtensionInterface $finder) {
			if (!$properties instanceof Configuration) {
				throw new \Exception("Configuration incompatible");
			}

			$urlRules = new ModuleRulesRule();
			$urlRules->addTest(new JsRegEx("\.(mp4|webm|wav|mp3|m4a|aac|oga)(\?.*)?$"));
			$urlRules->addUse(new UseEntry($finder->path("@UneakAssetsBundle:module/url-loader"), array(
				"name"  => new JsEval("isDebug ? '[path][name].[ext]?[hash:8]' : '[hash:8].[ext]'"),
				"limit" => 10000,
			)));

			$properties->module()->rules()->addRule($urlRules);


		}

	}
