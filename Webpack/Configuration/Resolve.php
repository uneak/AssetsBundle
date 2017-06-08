<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	use Uneak\AssetsBundle\Javascript\JsItem\JsAbstract;

	class Resolve extends JsAbstract implements ExtraInterface {

		use ExtraTrait;

		/**
		 * @var ResolveAlias
		 */
		public $alias;

		private $aliasFields;
		private $descriptionFiles;
		private $enforceExtension;
		private $enforceModuleExtension;
		private $extensions = array();
		private $mainFields;
		private $mainFiles;
		private $modules;
		private $unsafeCache;
		//	resolveLoader
		//	resolveLoader.moduleExtensions
		private $plugins;
		private $symlinks;
		private $cachePredicate;

		/**
		 * @return \Uneak\AssetsBundle\Webpack\Configuration\ResolveAlias
		 */
		public function alias() {
			if (!$this->alias) {
				$this->alias = new ResolveAlias();
			}
			return $this->alias;
		}

		/**
		 * @return mixed
		 */
		public function getAliasFields() {
			return $this->aliasFields;
		}

		/**
		 * @param mixed $aliasFields
		 *
		 * @return Resolve
		 */
		public function setAliasFields($aliasFields) {
			$this->aliasFields = $aliasFields;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getDescriptionFiles() {
			return $this->descriptionFiles;
		}

		/**
		 * @param mixed $descriptionFiles
		 *
		 * @return Resolve
		 */
		public function setDescriptionFiles($descriptionFiles) {
			$this->descriptionFiles = $descriptionFiles;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getEnforceExtension() {
			return $this->enforceExtension;
		}

		/**
		 * @param mixed $enforceExtension
		 *
		 * @return Resolve
		 */
		public function setEnforceExtension($enforceExtension) {
			$this->enforceExtension = $enforceExtension;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getEnforceModuleExtension() {
			return $this->enforceModuleExtension;
		}

		/**
		 * @param mixed $enforceModuleExtension
		 *
		 * @return Resolve
		 */
		public function setEnforceModuleExtension($enforceModuleExtension) {
			$this->enforceModuleExtension = $enforceModuleExtension;

			return $this;
		}

		/**
		 * @return array
		 */
		public function getExtensions() {
			return $this->extensions;
		}

		/**
		 * @param array $extensions
		 *
		 * @return Resolve
		 */
		public function setExtensions(array $extensions) {
			foreach ($extensions as $extension) {
				$this->addExtension($extension);
			}

			return $this;
		}

		/**
		 * @param string $extension
		 *
		 * @return Resolve
		 */
		public function addExtension($extension) {
			if (false === array_search($extension, $this->extensions)) {
				$this->extensions[] = $extension;
			}

			return $this;
		}

		public function removeExtension($extension) {
			if (false !== $i = array_search($extension, $this->extensions)) {
				unset($this->extensions[$i]);
			}

			return $this;
		}

		public function hasExtension($extension) {
			return (false !== array_search($extension, $this->extensions));
		}

		/**
		 * @return mixed
		 */
		public function getMainFields() {
			return $this->mainFields;
		}

		/**
		 * @param mixed $mainFields
		 *
		 * @return Resolve
		 */
		public function setMainFields($mainFields) {
			$this->mainFields = $mainFields;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getMainFiles() {
			return $this->mainFiles;
		}

		/**
		 * @param mixed $mainFiles
		 *
		 * @return Resolve
		 */
		public function setMainFiles($mainFiles) {
			$this->mainFiles = $mainFiles;

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
		 * @return Resolve
		 */
		public function setModules($modules) {
			$this->modules = $modules;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getUnsafeCache() {
			return $this->unsafeCache;
		}

		/**
		 * @param mixed $unsafeCache
		 *
		 * @return Resolve
		 */
		public function setUnsafeCache($unsafeCache) {
			$this->unsafeCache = $unsafeCache;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getPlugins() {
			return $this->plugins;
		}

		/**
		 * @param mixed $plugins
		 *
		 * @return Resolve
		 */
		public function setPlugins($plugins) {
			$this->plugins = $plugins;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getSymlinks() {
			return $this->symlinks;
		}

		/**
		 * @param mixed $symlinks
		 *
		 * @return Resolve
		 */
		public function setSymlinks($symlinks) {
			$this->symlinks = $symlinks;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getCachePredicate() {
			return $this->cachePredicate;
		}

		/**
		 * @param mixed $cachePredicate
		 *
		 * @return Resolve
		 */
		public function setCachePredicate($cachePredicate) {
			$this->cachePredicate = $cachePredicate;

			return $this;
		}


		protected function _getData() {
			return array(
				'aliasFields'            => $this->getAliasFields(),
				'descriptionFiles'       => $this->getDescriptionFiles(),
				'enforceExtension'       => $this->getEnforceExtension(),
				'enforceModuleExtension' => $this->getEnforceModuleExtension(),
				'extensions'             => $this->getExtensions(),
				'mainFields'             => $this->getMainFields(),
				'mainFiles'              => $this->getMainFiles(),
				'modules'                => $this->getModules(),
				'unsafeCache'            => $this->getUnsafeCache(),
				'plugins'                => $this->getPlugins(),
				'symlinks'               => $this->getSymlinks(),
				'cachePredicate'         => $this->getCachePredicate(),
				'alias'                  => $this->alias,
			);
		}


	}
