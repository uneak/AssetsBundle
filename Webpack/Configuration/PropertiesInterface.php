<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	interface PropertiesInterface {

		/**
		 * @return \Uneak\AssetsBundle\Javascript\JsItem\JsList
		 */
		public function header();

		/**
		 * @return \Uneak\AssetsBundle\Javascript\JsItem\JsList
		 */
		public function footer();

		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Requires
		 */
		public function requires();

		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\Imports
		 */
		public function imports();

	}
