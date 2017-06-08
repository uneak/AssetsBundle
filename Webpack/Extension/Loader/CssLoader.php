<?php

	namespace Uneak\AssetsBundle\Webpack\Extension\Loader;

	use Uneak\AssetsBundle\Finder\FinderExtensionInterface;
	use Uneak\AssetsBundle\Webpack\Configuration\Configuration;
	use Uneak\AssetsBundle\Webpack\Configuration\ModuleRulesRule;
	use Uneak\AssetsBundle\Webpack\Configuration\PropertiesInterface;
	use Uneak\AssetsBundle\Webpack\Configuration\UseEntry;
	use Uneak\AssetsBundle\Webpack\Extension\AbstractExtension;
	use Uneak\AssetsBundle\Javascript\JsItem\JsEval;
	use Uneak\AssetsBundle\Javascript\JsItem\JsFunction;
	use Uneak\AssetsBundle\Javascript\JsItem\JsInstance;
	use Uneak\AssetsBundle\Javascript\JsItem\JsRegEx;

	class CssLoader extends AbstractExtension {

		public function build(PropertiesInterface $properties, FinderExtensionInterface $finder) {
			if (!$properties instanceof Configuration) {
				throw new \Exception("Configuration incompatible");
			}

			$cssRules = new ModuleRulesRule();
			$cssRules->addTest(new JsRegEx("\.css"));
			$cssRules->addUse(new UseEntry($finder->path("@UneakAssetsBundle:module/isomorphic-style-loader")));
			$cssRules->addUse(new UseEntry($finder->path("@UneakAssetsBundle:module/css-loader"), array(
				"importLoaders"   => 1,
				"sourceMap"       => new JsEval("isDebug"),
				"modules"         => true,
				"localIdentName"  => new JsEval("isDebug ? '[name]-[local]-[hash:base64:5]' : '[hash:base64:5]'"),
				"minimize"        => new JsEval("!isDebug"),
				"discardComments" => array(
					"removeAll" => true
				),
			)));

			$cssRules->addUse(new UseEntry($finder->path("@UneakAssetsBundle:module/postcss-loader"), array(
//				"config" => $npmFinder->path("@UneakWebpackBundle/Tools/postcss.config.js"),
				"plugins" => new JsFunction(null, "loader", array(
					new JsInstance($finder->path("@UneakAssetsBundle:module/postcss-global-import"), null, JsInstance::REQUIRE),
					new JsInstance($finder->path("@UneakAssetsBundle:module/postcss-import"), null, JsInstance::REQUIRE),
					new JsInstance($finder->path("@UneakAssetsBundle:module/postcss-custom-properties"), null, JsInstance::REQUIRE),
					new JsInstance($finder->path("@UneakAssetsBundle:module/postcss-custom-media"), null, JsInstance::REQUIRE),
					new JsInstance($finder->path("@UneakAssetsBundle:module/postcss-media-minmax"), null, JsInstance::REQUIRE),
					new JsInstance($finder->path("@UneakAssetsBundle:module/postcss-custom-selectors"), null, JsInstance::REQUIRE),
					new JsInstance($finder->path("@UneakAssetsBundle:module/postcss-calc"), null, JsInstance::REQUIRE),
					new JsInstance($finder->path("@UneakAssetsBundle:module/postcss-nesting"), null, JsInstance::REQUIRE),
					new JsInstance($finder->path("@UneakAssetsBundle:module/postcss-nested"), null, JsInstance::REQUIRE),
					new JsInstance($finder->path("@UneakAssetsBundle:module/postcss-color-function"), null, JsInstance::REQUIRE),
					new JsInstance($finder->path("@UneakAssetsBundle:module/pleeease-filters"), null, JsInstance::REQUIRE),
					new JsInstance($finder->path("@UneakAssetsBundle:module/pixrem"), null, JsInstance::REQUIRE),
					new JsInstance($finder->path("@UneakAssetsBundle:module/postcss-selector-matches"), null, JsInstance::REQUIRE),
					new JsInstance($finder->path("@UneakAssetsBundle:module/postcss-selector-not"), null, JsInstance::REQUIRE),
					new JsInstance($finder->path("@UneakAssetsBundle:module/postcss-flexbugs-fixes"), null, JsInstance::REQUIRE),
					new JsInstance($finder->path("@UneakAssetsBundle:module/autoprefixer"), null, JsInstance::REQUIRE),
				))
			)));
			$properties->module()->rules()->addRule($cssRules);

		}

	}
