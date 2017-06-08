<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	interface ConfigurationPropertiesInterface extends PropertiesInterface {
		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\DevServer
		 */
		public function devServer();
		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Entry
		 */
		public function entry();
		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Externals
		 */
		public function getExternals();
		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Module
		 */
		public function module();
		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Node
		 */
		public function node();
		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Output
		 */
		public function output();
		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Performance
		 */
		public function performance();
		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Plugin
		 */
		public function plugins();
		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Resolve
		 */
		public function resolve();
		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Stats
		 */
		public function stats();
		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\WatchOptions
		 */
		public function watchOptions();

	}
