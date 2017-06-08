<?php

	namespace Uneak\AssetsBundle\Webpack\Extension;

	use Uneak\AssetsBundle\Finder\FinderExtensionInterface;
	use Uneak\AssetsBundle\Webpack\Configuration\PropertiesInterface;
	use Uneak\AssetsBundle\Webpack\Configuration\Webpack;
	use Uneak\AssetsBundle\Javascript\JsItem\JsEval;
	use Uneak\AssetsBundle\Javascript\JsItem\JsVar;

	class WebpackExtension extends AbstractExtension {

		public function build(PropertiesInterface $properties, FinderExtensionInterface $finder) {
			if (!$properties instanceof Webpack) {
				throw new \Exception("Configuration incompatible");
			}

			$properties->imports()->addImport("path", "path");
			$properties->imports()->addImport("webpack", $finder->path("@UneakAssetsBundle:module/webpack"));
			$properties->imports()->addImport("AssetsPlugin", $finder->path("@UneakAssetsBundle:module/assets-webpack-plugin"));
			$properties->imports()->addImport(array("BundleAnalyzerPlugin"), $finder->path("@UneakAssetsBundle:module/webpack-bundle-analyzer"));
	
			$properties->header()
				->addLine(new JsVar("isDebug", new JsEval("!process.argv.includes('--release')"), JsVar::CONST))
				->addLine(new JsVar("isVerbose", new JsEval("process.argv.includes('--verbose')"), JsVar::CONST))
				->addLine(new JsVar("isAnalyze", new JsEval("process.argv.includes('--analyze') || process.argv.includes('--analyse')"), JsVar::CONST))
				
				->addLine(new JsVar("ENGINES", array(
					"node" => ">=6.5",
    				"npm" => ">=3.10"
				), JsVar::CONST))
			;

		}

	}
