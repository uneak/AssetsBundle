<?php

	namespace Uneak\AssetsBundle\Finder;

	interface FinderExtensionInterface {
		/**
		 * @param $key
		 *
		 * @return string
		 * @throws \Uneak\AssetsBundle\Exception\LinkNotFoundException
		 */
		public function path($key);
		/**
		 * @param $key
		 *
		 * @return string
		 * @throws \Uneak\AssetsBundle\Exception\LinkNotFoundException
		 */
		public function file($key);

		/**
		 * @param $key
		 *
		 * @return string
		 * @throws \Uneak\AssetsBundle\Exception\LinkNotFoundException
		 */
		public function find($key);
			
		/**
		 * @return array
		 */
		public function all();
		/**
		 * @param $key
		 *
		 * @return bool
		 */
		public function has($key);
	}
