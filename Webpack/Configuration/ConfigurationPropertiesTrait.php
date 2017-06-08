<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	trait ConfigurationPropertiesTrait {
		use PropertiesTrait;

		private $devServer;
		private $entry;
		private $externals;
		private $module;
		private $node;
		private $output;
		private $performance;
		private $plugins;
		private $resolve;
		private $stats;
		private $watchOptions;


		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\DevServer
		 */
		public function devServer() {
			if (!$this->devServer) {
				$this->devServer = new DevServer();
			}
			return $this->devServer;
		}

		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Entry
		 */
		public function entry() {
			if (!$this->entry) {
				$this->entry = new Entry();
			}
			return $this->entry;
		}

		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Externals
		 */
		public function getExternals() {
			if (!$this->externals) {
				$this->externals = new Externals();
			}
			return $this->externals;
		}

		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Module
		 */
		public function module() {
			if (!$this->module) {
				$this->module = new Module();
			}
			return $this->module;
		}

		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Node
		 */
		public function node() {
			if (!$this->node) {
				$this->node = new Node();
			}
			return $this->node;
		}

		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Output
		 */
		public function output() {
			if (!$this->output) {
				$this->output = new Output();
			}
			return $this->output;
		}

		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Performance
		 */
		public function performance() {
			if (!$this->performance) {
				$this->performance = new Performance();
			}
			return $this->performance;
		}

		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Plugin
		 */
		public function plugins() {
			if (!$this->plugins) {
				$this->plugins = new Plugin();
			}
			return $this->plugins;
		}

		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Resolve
		 */
		public function resolve() {
			if (!$this->resolve) {
				$this->resolve = new Resolve();
			}
			return $this->resolve;
		}

		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Stats
		 */
		public function stats() {
			if (!$this->stats) {
				$this->stats = new Stats();
			}
			return $this->stats;
		}

		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\WatchOptions
		 */
		public function watchOptions() {
			if (!$this->watchOptions) {
				$this->watchOptions = new WatchOptions();
			}
			return $this->watchOptions;
		}

	}
