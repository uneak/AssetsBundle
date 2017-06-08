<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	use Uneak\AssetsBundle\Javascript\JsItem\JsAbstract;

	class Performance extends JsAbstract implements ExtraInterface {

		use ExtraTrait;

		private $hints;
		private $maxEntrypointSize;
		private $maxAssetSize;
		private $assetFilter;

		/**
		 * @return mixed
		 */
		public function getHints() {
			return $this->hints;
		}

		/**
		 * @param mixed $hints
		 *
		 * @return Performance
		 */
		public function setHints($hints) {
			$this->hints = $hints;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getMaxEntrypointSize() {
			return $this->maxEntrypointSize;
		}

		/**
		 * @param mixed $maxEntrypointSize
		 *
		 * @return Performance
		 */
		public function setMaxEntrypointSize($maxEntrypointSize) {
			$this->maxEntrypointSize = $maxEntrypointSize;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getMaxAssetSize() {
			return $this->maxAssetSize;
		}

		/**
		 * @param mixed $maxAssetSize
		 *
		 * @return Performance
		 */
		public function setMaxAssetSize($maxAssetSize) {
			$this->maxAssetSize = $maxAssetSize;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getAssetFilter() {
			return $this->assetFilter;
		}

		/**
		 * @param mixed $assetFilter
		 *
		 * @return Performance
		 */
		public function setAssetFilter($assetFilter) {
			$this->assetFilter = $assetFilter;
			return $this;
		}

		protected function _getData() {
			return array(
				'hints'             => $this->getHints(),
				'maxEntrypointSize' => $this->getMaxEntrypointSize(),
				'maxAssetSize'      => $this->getMaxAssetSize(),
				'assetFilter'       => $this->getAssetFilter(),
			);
		}

	}
