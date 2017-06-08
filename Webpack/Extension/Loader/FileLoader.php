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

	class FileLoader extends AbstractExtension {

		public function build(PropertiesInterface $properties, FinderExtensionInterface $finder) {
			if (!$properties instanceof Configuration) {
				throw new \Exception("Configuration incompatible");
			}

			$fileRules = new ModuleRulesRule();
			$fileRules->addTest(new JsRegEx("\.(ico|jpg|jpeg|png|gif|eot|otf|webp|svg|ttf|woff|woff2)(\?.*)?$"));
			$fileRules->addUse(new UseEntry($finder->path("@UneakAssetsBundle:module/file-loader"), array(
				"name" => new JsEval("isDebug ? '[path][name].[ext]?[hash:8]' : '[hash:8].[ext]'"),
			)));

			$properties->module()->rules()->addRule($fileRules);

		}

	}
