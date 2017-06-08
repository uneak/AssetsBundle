<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;
	
	use Uneak\AssetsBundle\Javascript\JsItem\JsAbstract;

	class Stats extends JsAbstract implements ExtraInterface {

		use ExtraTrait;

		private $assets;
		private $assetsSort;
		private $cached;
		private $cachedAssets;
		private $children;
		private $chunks;
		private $chunkModules;
		private $chunkOrigins;
		private $chunksSort;
		private $context;
		private $colors;
		private $depth;
		private $entrypoints;
		private $errors;
		private $errorDetails;
		private $exclude;
		private $hash;
		private $maxModules;
		private $modules;
		private $modulesSort;
		private $performance;
		private $providedExports;
		private $publicPath;
		private $reasons;
		private $source;
		private $timings;
		private $usedExports;
		private $version;
		private $warnings;
		private $warningsFilter;

		/**
		 * @return mixed
		 */
		public function getAssets() {
			return $this->assets;
		}

		/**
		 * @param mixed $assets
		 *
		 * @return Stats
		 */
		public function setAssets($assets) {
			$this->assets = $assets;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getAssetsSort() {
			return $this->assetsSort;
		}

		/**
		 * @param mixed $assetsSort
		 *
		 * @return Stats
		 */
		public function setAssetsSort($assetsSort) {
			$this->assetsSort = $assetsSort;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getCached() {
			return $this->cached;
		}

		/**
		 * @param mixed $cached
		 *
		 * @return Stats
		 */
		public function setCached($cached) {
			$this->cached = $cached;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getCachedAssets() {
			return $this->cachedAssets;
		}

		/**
		 * @param mixed $cachedAssets
		 *
		 * @return Stats
		 */
		public function setCachedAssets($cachedAssets) {
			$this->cachedAssets = $cachedAssets;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getChildren() {
			return $this->children;
		}

		/**
		 * @param mixed $children
		 *
		 * @return Stats
		 */
		public function setChildren($children) {
			$this->children = $children;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getChunks() {
			return $this->chunks;
		}

		/**
		 * @param mixed $chunks
		 *
		 * @return Stats
		 */
		public function setChunks($chunks) {
			$this->chunks = $chunks;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getChunkModules() {
			return $this->chunkModules;
		}

		/**
		 * @param mixed $chunkModules
		 *
		 * @return Stats
		 */
		public function setChunkModules($chunkModules) {
			$this->chunkModules = $chunkModules;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getChunkOrigins() {
			return $this->chunkOrigins;
		}

		/**
		 * @param mixed $chunkOrigins
		 *
		 * @return Stats
		 */
		public function setChunkOrigins($chunkOrigins) {
			$this->chunkOrigins = $chunkOrigins;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getChunksSort() {
			return $this->chunksSort;
		}

		/**
		 * @param mixed $chunksSort
		 *
		 * @return Stats
		 */
		public function setChunksSort($chunksSort) {
			$this->chunksSort = $chunksSort;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getContext() {
			return $this->context;
		}

		/**
		 * @param mixed $context
		 *
		 * @return Stats
		 */
		public function setContext($context) {
			$this->context = $context;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getColors() {
			return $this->colors;
		}

		/**
		 * @param mixed $colors
		 *
		 * @return Stats
		 */
		public function setColors($colors) {
			$this->colors = $colors;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getDepth() {
			return $this->depth;
		}

		/**
		 * @param mixed $depth
		 *
		 * @return Stats
		 */
		public function setDepth($depth) {
			$this->depth = $depth;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getEntrypoints() {
			return $this->entrypoints;
		}

		/**
		 * @param mixed $entrypoints
		 *
		 * @return Stats
		 */
		public function setEntrypoints($entrypoints) {
			$this->entrypoints = $entrypoints;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getErrors() {
			return $this->errors;
		}

		/**
		 * @param mixed $errors
		 *
		 * @return Stats
		 */
		public function setErrors($errors) {
			$this->errors = $errors;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getErrorDetails() {
			return $this->errorDetails;
		}

		/**
		 * @param mixed $errorDetails
		 *
		 * @return Stats
		 */
		public function setErrorDetails($errorDetails) {
			$this->errorDetails = $errorDetails;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getExclude() {
			return $this->exclude;
		}

		/**
		 * @param mixed $exclude
		 *
		 * @return Stats
		 */
		public function setExclude($exclude) {
			$this->exclude = $exclude;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getHash() {
			return $this->hash;
		}

		/**
		 * @param mixed $hash
		 *
		 * @return Stats
		 */
		public function setHash($hash) {
			$this->hash = $hash;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getMaxModules() {
			return $this->maxModules;
		}

		/**
		 * @param mixed $maxModules
		 *
		 * @return Stats
		 */
		public function setMaxModules($maxModules) {
			$this->maxModules = $maxModules;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getModules() {
			return $this->modules;
		}

		/**
		 * @param mixed $modules
		 *
		 * @return Stats
		 */
		public function setModules($modules) {
			$this->modules = $modules;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getModulesSort() {
			return $this->modulesSort;
		}

		/**
		 * @param mixed $modulesSort
		 *
		 * @return Stats
		 */
		public function setModulesSort($modulesSort) {
			$this->modulesSort = $modulesSort;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getPerformance() {
			return $this->performance;
		}

		/**
		 * @param mixed $performance
		 *
		 * @return Stats
		 */
		public function setPerformance($performance) {
			$this->performance = $performance;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getProvidedExports() {
			return $this->providedExports;
		}

		/**
		 * @param mixed $providedExports
		 *
		 * @return Stats
		 */
		public function setProvidedExports($providedExports) {
			$this->providedExports = $providedExports;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getPublicPath() {
			return $this->publicPath;
		}

		/**
		 * @param mixed $publicPath
		 *
		 * @return Stats
		 */
		public function setPublicPath($publicPath) {
			$this->publicPath = $publicPath;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getReasons() {
			return $this->reasons;
		}

		/**
		 * @param mixed $reasons
		 *
		 * @return Stats
		 */
		public function setReasons($reasons) {
			$this->reasons = $reasons;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getSource() {
			return $this->source;
		}

		/**
		 * @param mixed $source
		 *
		 * @return Stats
		 */
		public function setSource($source) {
			$this->source = $source;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getTimings() {
			return $this->timings;
		}

		/**
		 * @param mixed $timings
		 *
		 * @return Stats
		 */
		public function setTimings($timings) {
			$this->timings = $timings;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getUsedExports() {
			return $this->usedExports;
		}

		/**
		 * @param mixed $usedExports
		 *
		 * @return Stats
		 */
		public function setUsedExports($usedExports) {
			$this->usedExports = $usedExports;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getVersion() {
			return $this->version;
		}

		/**
		 * @param mixed $version
		 *
		 * @return Stats
		 */
		public function setVersion($version) {
			$this->version = $version;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getWarnings() {
			return $this->warnings;
		}

		/**
		 * @param mixed $warnings
		 *
		 * @return Stats
		 */
		public function setWarnings($warnings) {
			$this->warnings = $warnings;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getWarningsFilter() {
			return $this->warningsFilter;
		}

		/**
		 * @param mixed $warningsFilter
		 *
		 * @return Stats
		 */
		public function setWarningsFilter($warningsFilter) {
			$this->warningsFilter = $warningsFilter;

			return $this;
		}


		protected function _getData() {
			return array(
				"assets"          => $this->getAssets(),
				"assetsSort"      => $this->getAssetsSort(),
				"cached"          => $this->getCached(),
				"cachedAssets"    => $this->getCachedAssets(),
				"children"        => $this->getChildren(),
				"chunks"          => $this->getChunks(),
				"chunkModules"    => $this->getChunkModules(),
				"chunkOrigins"    => $this->getChunkOrigins(),
				"chunksSort"      => $this->getChunksSort(),
				"context"         => $this->getContext(),
				"colors"          => $this->getColors(),
				"depth"           => $this->getDepth(),
				"entrypoints"     => $this->getEntrypoints(),
				"errors"          => $this->getErrors(),
				"errorDetails"    => $this->getErrorDetails(),
				"exclude"         => $this->getExclude(),
				"hash"            => $this->getHash(),
				"maxModules"      => $this->getMaxModules(),
				"modules"         => $this->getModules(),
				"modulesSort"     => $this->getModulesSort(),
				"performance"     => $this->getPerformance(),
				"providedExports" => $this->getProvidedExports(),
				"publicPath"      => $this->getPublicPath(),
				"reasons"         => $this->getReasons(),
				"source"          => $this->getSource(),
				"timings"         => $this->getTimings(),
				"usedExports"     => $this->getUsedExports(),
				"version"         => $this->getVersion(),
				"warnings"        => $this->getWarnings(),
				"warningsFilter"  => $this->getWarningsFilter(),
			);
		}

	}
