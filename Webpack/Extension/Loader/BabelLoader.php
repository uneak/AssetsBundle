<?php

	namespace Uneak\AssetsBundle\Webpack\Extension\Loader;

	use Uneak\AssetsBundle\Finder\FinderExtensionInterface;
	use Uneak\AssetsBundle\Webpack\Configuration\Configuration;
	use Uneak\AssetsBundle\Webpack\Configuration\ModuleRulesRule;
	use Uneak\AssetsBundle\Webpack\Configuration\PropertiesInterface;
	use Uneak\AssetsBundle\Webpack\Configuration\UseEntry;
	use Uneak\AssetsBundle\Webpack\Extension\AbstractExtension;
	use Uneak\AssetsBundle\Javascript\JsItem\JsArrayExtend;
	use Uneak\AssetsBundle\Javascript\JsItem\JsEval;
	use Uneak\AssetsBundle\Javascript\JsItem\JsRegEx;
	use Uneak\AssetsBundle\Javascript\JsItem\JsVar;

	class BabelLoader extends AbstractExtension {

		public function build(PropertiesInterface $properties, FinderExtensionInterface $finder) {
			if (!$properties instanceof Configuration) {
				throw new \Exception("Configuration incompatible");
			}

			$properties->header()
				->addLine(new JsVar("BROWSERS_LIST", array(
					">1%",
					"last 4 versions",
					"Firefox ESR",
					"not ie < 9",
				), JsVar::CONST))
				->addLine(new JsVar("BABEL_PRESETS", array(
					array(
						"env" => array(
							"targets"     => array(
								"browsers" => new JsEval("BROWSERS_LIST"),
							),
							"modules"     => false,
							"useBuiltIns" => false,
							"debug"       => false,
						)
					),
					$finder->path("@UneakAssetsBundle:module/babel-preset-stage-2"),
					$finder->path("@UneakAssetsBundle:module/babel-preset-react"),
					new JsArrayExtend("isDebug", array(), array('react-optimize'))
				), JsVar::CONST))
			;



			$babelRules = new ModuleRulesRule();
			$babelRules->addTest(new JsRegEx("\.jsx?$"));
			$babelRules->addUse(new UseEntry($finder->path("@UneakAssetsBundle:module/babel-loader"), array(
				"cacheDirectory" => new JsEval("isDebug"),
				"babelrc"        => false,
				"presets"        => new JsEval("BABEL_PRESETS"),
				"plugins"        => array(
					new JsArrayExtend("isDebug", array($finder->path("@UneakAssetsBundle:module/babel-plugin-transform-react-jsx-source")), array()),
					new JsArrayExtend("isDebug", array($finder->path("@UneakAssetsBundle:module/babel-plugin-transform-react-jsx-self")), array()),
				),
			)));
			
			
			
//			$babelRules->addLoader("babel-loader");
//			$babelRules->addLoader($npmFinder->getPath("@UneakAssetsBundle:module/babel-loader"));
//			$babelRules->addInclude(new JsEval("path.resolve(__dirname, '../src')"));
//			$babelRules->setQueries(array(
//				"cacheDirectory" => new JsEval("isDebug"),
//				"babelrc"        => false,
//				"presets"        => new JsEval("BABEL_PRESETS"),
//				"plugins"        => array(
//					new JsArrayExtend("isDebug", array('transform-react-jsx-source'), array()),
//					new JsArrayExtend("isDebug", array('transform-react-jsx-self'), array()),
//				),
//			));
			$properties->module()->rules()->addRule($babelRules);


		}

	}
