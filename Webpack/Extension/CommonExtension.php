<?php

	namespace Uneak\AssetsBundle\Webpack\Extension;

	use Uneak\AssetsBundle\Finder\FinderExtensionInterface;
	use Uneak\AssetsBundle\Webpack\Configuration\Configuration;
	use Uneak\AssetsBundle\Webpack\Configuration\PropertiesInterface;
	use Uneak\AssetsBundle\Javascript\JsItem\JsEval;

	class CommonExtension extends AbstractExtension {

		public function build(PropertiesInterface $properties, FinderExtensionInterface $finder) {
			if (!$properties instanceof Configuration) {
				throw new \Exception("Configuration incompatible");
			}

			$properties->output()
				->setPath($finder->path("@Public/assets"))
				->setPublicPath("/assets/")
				->setPathinfo(new JsEval("isVerbose"))
			;
			
			$properties
				->addExtra("cache", new JsEval("isDebug"))
				->addExtra("bail", new JsEval("!isDebug"));

			$properties->stats()
				->setColors(true)
				->setReasons(new JsEval("isDebug"))
				->setHash(new JsEval("isVerbose"))
				->setVersion(new JsEval("isVerbose"))
				->setTimings(true)
				->setChunks(new JsEval("isVerbose"))
				->setChunkModules(new JsEval("isVerbose"))
				->setCached(new JsEval("isVerbose"))
				->setCachedAssets(new JsEval("isVerbose"));

		}

	}
