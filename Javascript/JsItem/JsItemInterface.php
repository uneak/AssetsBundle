<?php

	namespace Uneak\AssetsBundle\Javascript\JsItem;

	interface JsItemInterface {
		/**
		 * @return bool
		 */
		public function isEmpty();
		public function jsRender();
	}
