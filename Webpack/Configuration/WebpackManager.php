<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	use Symfony\Component\Config\ConfigCache;
	use Symfony\Component\Process\ProcessBuilder;
	use Uneak\AssetsBundle\Finder\FinderExtensionInterface;
	use Uneak\AssetsBundle\Javascript\Javascript;
	use Uneak\AssetsBundle\Javascript\JsItem\JsEval;
	use Uneak\AssetsBundle\Javascript\JsItem\JsExport;
	use Uneak\AssetsBundle\Javascript\JsItem\JsList;

	class WebpackManager {
		/**
		 * @var WebpackInterface[]
		 */
		private $webpacks = array();
		/**
		 * @var FinderExtensionInterface
		 */
		private $finder;
		/**
		 * @var string
		 */
		private $cacheDir;
		/**
		 * @var bool
		 */
		private $debug;


		public function __construct(FinderExtensionInterface $finder, $cacheDir, $debug) {
			$this->finder = $finder;
			$this->cacheDir = $cacheDir;
			$this->debug = $debug;
		}

		/**
		 * @param \Uneak\AssetsBundle\Webpack\Configuration\WebpackInterface $webpack
		 *
		 * @return $this
		 */
		public function addWebpack(WebpackInterface $webpack) {
			$this->webpacks[$webpack->getName()] = $webpack;
			return $this;
		}

		/**
		 * @param $name
		 *
		 * @return $this
		 */
		public function removeWebpack($name) {
			unset($this->webpacks[$name]);
			return $this;
		}

		/**
		 * @param $name
		 *
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\WebpackInterface
		 */
		public function getWebpack($name) {
			return $this->webpacks[$name];
		}

		/**
		 * @param $name
		 *
		 * @return bool
		 */
		public function hasWebpack($name) {
			return isset($this->webpacks[$name]);
		}

		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\WebpackInterface[]
		 */
		public function getWebpacks() {
			return $this->webpacks;
		}

		/**
		 * @param array $webpacks
		 *
		 * @return $this
		 */
		public function setWebpacks(array $webpacks) {
			foreach ($webpacks as $webpack) {
				$this->addWebpack($webpack);
			}
			return $this;
		}





		public function jsRender($name) {
			$webpack = clone $this->getWebpack($name);
			
			$exports = array();
			foreach ($webpack->getExtensions() as $extension) {
				$extension->build($webpack, $this->finder);
			}


			foreach ($webpack->getConfigurations() as $configuration) {
				foreach ($configuration->getExtensions() as $extension) {
					$extension->build($configuration, $this->finder);
				}
				if ($configuration->isExport()) {
					$exports[] = new JsEval($configuration->getName());
				}
			}

			$list = new JsList();
			$list->addLine(new JsEval("/** Generated by uneak/webpack-bundle. Do not modify. */"));
			$list->addLine($webpack->imports());
			foreach ($webpack->getConfigurations() as $name => $configuration) {
				$list->addLine($configuration->imports());
			}
			$list->addLine($webpack->requires());
			foreach ($webpack->getConfigurations() as $name => $configuration) {
				$list->addLine($configuration->requires());
			}
			$list->addLine($webpack->header());
			foreach ($webpack->getConfigurations() as $name => $configuration) {
				$list->addLine($configuration);
			}
			$list->addLine($webpack->footer());
			$list->addLine(new JsExport('default', $exports));

			return Javascript::encode($list);
		}


		public function dump($name) {
			$cache = new ConfigCache(sprintf("%s/webpack/webpack.%s.config.js", $this->cacheDir, $name), $this->debug);
			$cache->write($this->jsRender($name));
			return $cache->getPath();
		}


		/**
		 * @param string $name
		 * @param array $commands
		 *
		 * @return \Symfony\Component\Process\Process
		 */
		public function createProcess($name, array $commands = array()) {

			//			$webpackBin = $this->npmFinder->getPath("@UneakAssetsBundle:module", "webpack");
			$babelNodeBin = $this->finder->path("@UneakAssetsBundle:module/.bin/babel-node");
			$webpackNodePath = $this->finder->path("@UneakAssetsBundle:node");
			$runScript = $webpackNodePath . '/run.js';

			$builder = new ProcessBuilder();
			$builder->setWorkingDirectory($webpackNodePath);
			$builder->add($babelNodeBin);
//			$builder->add("--no-babelrc");
			$builder->add($runScript);
			foreach ($commands as $command) {
				$builder->add($command);
			}
			//			$builder->add($webpackBin);
			$builder->add("--config");
			$builder->add($this->dump($name));

//			$builder->add("--presets");
			//			$builder->add($this->npmFinder->getPath("@UneakAssetsBundle:module/babel-preset-es2015").",".$this->npmFinder->getPath("@UneakAssetsBundle:module/babel-preset-react"));
//			$builder->add("es2015,react");



			//			$builder->add($this->write());
			//			$builder->add("/Users/marcnoviris/Workspace/www/vhosts/incenty-server/var/cache/dev/webpack/webpack.config.js");

			$builder->setTimeout(600);

			return $builder->getProcess();
		}

	}
