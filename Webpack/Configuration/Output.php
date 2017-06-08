<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	use Uneak\AssetsBundle\Javascript\JsItem\JsAbstract;

	class Output extends JsAbstract implements ExtraInterface {

		use ExtraTrait;

		private $chunkFilename;
		private $crossOriginLoading;
		private $devtoolLineToLine;
		private $filename;
		private $hotUpdateChunkFilename;
		private $hotUpdateFunction;
		private $hotUpdateMainFilename;
		private $jsonpFunction;
		private $library;
		private $libraryTarget;
		private $path;
		private $sourceMapFilename;
		private $devtoolFallbackModuleFilenameTemplate;
		private $devtoolModuleFilenameTemplate;
		private $hashDigest;
		private $hashDigestLength;
		private $hashFunction;
		private $hashSalt;
		private $pathinfo;
		private $publicPath;
		private $sourcePrefix;
		private $strictModuleExceptionHandling;
		private $umdNamedDefine;


		/**
		 * @return null
		 */
		public function getChunkFilename() {
			return $this->chunkFilename;
		}

		/**
		 * @param null $chunkFilename
		 *
		 * @return $this
		 */
		public function setChunkFilename($chunkFilename) {
			$this->chunkFilename = $chunkFilename;

			return $this;
		}

		/**
		 * @return null
		 */
		public function getCrossOriginLoading() {
			return $this->crossOriginLoading;
		}

		/**
		 * @param null $crossOriginLoading
		 *
		 * @return $this
		 */
		public function setCrossOriginLoading($crossOriginLoading) {
			$this->crossOriginLoading = $crossOriginLoading;

			return $this;
		}

		/**
		 * @return null
		 */
		public function getDevtoolLineToLine() {
			return $this->devtoolLineToLine;
		}

		/**
		 * @param null $devtoolLineToLine
		 *
		 * @return $this
		 */
		public function setDevtoolLineToLine($devtoolLineToLine) {
			$this->devtoolLineToLine = $devtoolLineToLine;

			return $this;
		}

		/**
		 * @return null
		 */
		public function getFilename() {
			return $this->filename;
		}

		/**
		 * @param null $filename
		 *
		 * @return $this
		 */
		public function setFilename($filename) {
			$this->filename = $filename;

			return $this;
		}

		/**
		 * @return null
		 */
		public function getHotUpdateChunkFilename() {
			return $this->hotUpdateChunkFilename;
		}

		/**
		 * @param null $hotUpdateChunkFilename
		 *
		 * @return $this
		 */
		public function setHotUpdateChunkFilename($hotUpdateChunkFilename) {
			$this->hotUpdateChunkFilename = $hotUpdateChunkFilename;

			return $this;
		}

		/**
		 * @return null
		 */
		public function getHotUpdateFunction() {
			return $this->hotUpdateFunction;
		}

		/**
		 * @param null $hotUpdateFunction
		 *
		 * @return $this
		 */
		public function setHotUpdateFunction($hotUpdateFunction) {
			$this->hotUpdateFunction = $hotUpdateFunction;

			return $this;
		}

		/**
		 * @return null
		 */
		public function getHotUpdateMainFilename() {
			return $this->hotUpdateMainFilename;
		}

		/**
		 * @param null $hotUpdateMainFilename
		 *
		 * @return $this
		 */
		public function setHotUpdateMainFilename($hotUpdateMainFilename) {
			$this->hotUpdateMainFilename = $hotUpdateMainFilename;

			return $this;
		}

		/**
		 * @return null
		 */
		public function getJsonpFunction() {
			return $this->jsonpFunction;
		}

		/**
		 * @param null $jsonpFunction
		 *
		 * @return $this
		 */
		public function setJsonpFunction($jsonpFunction) {
			$this->jsonpFunction = $jsonpFunction;

			return $this;
		}

		/**
		 * @return null
		 */
		public function getLibrary() {
			return $this->library;
		}

		/**
		 * @param null $library
		 *
		 * @return $this
		 */
		public function setLibrary($library) {
			$this->library = $library;

			return $this;
		}

		/**
		 * @return null
		 */
		public function getLibraryTarget() {
			return $this->libraryTarget;
		}

		/**
		 * @param null $libraryTarget
		 *
		 * @return $this
		 */
		public function setLibraryTarget($libraryTarget) {
			$this->libraryTarget = $libraryTarget;

			return $this;
		}

		/**
		 * @return null
		 */
		public function getPath() {
			return $this->path;
		}

		/**
		 * @param null $path
		 *
		 * @return $this
		 */
		public function setPath($path) {
			$this->path = $path;

			return $this;
		}

		/**
		 * @return null
		 */
		public function getSourceMapFilename() {
			return $this->sourceMapFilename;
		}

		/**
		 * @param null $sourceMapFilename
		 *
		 * @return $this
		 */
		public function setSourceMapFilename($sourceMapFilename) {
			$this->sourceMapFilename = $sourceMapFilename;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getDevtoolFallbackModuleFilenameTemplate() {
			return $this->devtoolFallbackModuleFilenameTemplate;
		}

		/**
		 * @param mixed $devtoolFallbackModuleFilenameTemplate
		 *
		 * @return Output
		 */
		public function setDevtoolFallbackModuleFilenameTemplate($devtoolFallbackModuleFilenameTemplate) {
			$this->devtoolFallbackModuleFilenameTemplate = $devtoolFallbackModuleFilenameTemplate;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getDevtoolModuleFilenameTemplate() {
			return $this->devtoolModuleFilenameTemplate;
		}

		/**
		 * @param mixed $devtoolModuleFilenameTemplate
		 *
		 * @return Output
		 */
		public function setDevtoolModuleFilenameTemplate($devtoolModuleFilenameTemplate) {
			$this->devtoolModuleFilenameTemplate = $devtoolModuleFilenameTemplate;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getHashDigest() {
			return $this->hashDigest;
		}

		/**
		 * @param mixed $hashDigest
		 *
		 * @return Output
		 */
		public function setHashDigest($hashDigest) {
			$this->hashDigest = $hashDigest;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getHashDigestLength() {
			return $this->hashDigestLength;
		}

		/**
		 * @param mixed $hashDigestLength
		 *
		 * @return Output
		 */
		public function setHashDigestLength($hashDigestLength) {
			$this->hashDigestLength = $hashDigestLength;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getHashFunction() {
			return $this->hashFunction;
		}

		/**
		 * @param mixed $hashFunction
		 *
		 * @return Output
		 */
		public function setHashFunction($hashFunction) {
			$this->hashFunction = $hashFunction;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getHashSalt() {
			return $this->hashSalt;
		}

		/**
		 * @param mixed $hashSalt
		 *
		 * @return Output
		 */
		public function setHashSalt($hashSalt) {
			$this->hashSalt = $hashSalt;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getPathinfo() {
			return $this->pathinfo;
		}

		/**
		 * @param mixed $pathinfo
		 *
		 * @return Output
		 */
		public function setPathinfo($pathinfo) {
			$this->pathinfo = $pathinfo;

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
		 * @return Output
		 */
		public function setPublicPath($publicPath) {
			$this->publicPath = $publicPath;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getSourcePrefix() {
			return $this->sourcePrefix;
		}

		/**
		 * @param mixed $sourcePrefix
		 *
		 * @return Output
		 */
		public function setSourcePrefix($sourcePrefix) {
			$this->sourcePrefix = $sourcePrefix;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getStrictModuleExceptionHandling() {
			return $this->strictModuleExceptionHandling;
		}

		/**
		 * @param mixed $strictModuleExceptionHandling
		 *
		 * @return Output
		 */
		public function setStrictModuleExceptionHandling($strictModuleExceptionHandling) {
			$this->strictModuleExceptionHandling = $strictModuleExceptionHandling;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getUmdNamedDefine() {
			return $this->umdNamedDefine;
		}

		/**
		 * @param mixed $umdNamedDefine
		 *
		 * @return Output
		 */
		public function setUmdNamedDefine($umdNamedDefine) {
			$this->umdNamedDefine = $umdNamedDefine;

			return $this;
		}


		protected function _getData() {
			return array(
				'chunkFilename'                         => $this->getChunkFilename(),
				'crossOriginLoading'                    => $this->getCrossOriginLoading(),
				'devtoolLineToLine'                     => $this->getDevtoolLineToLine(),
				'filename'                              => $this->getFilename(),
				'hotUpdateChunkFilename'                => $this->getHotUpdateChunkFilename(),
				'hotUpdateFunction'                     => $this->getHotUpdateFunction(),
				'hotUpdateMainFilename'                 => $this->getHotUpdateMainFilename(),
				'jsonpFunction'                         => $this->getJsonpFunction(),
				'library'                               => $this->getLibrary(),
				'libraryTarget'                         => $this->getLibraryTarget(),
				'path'                                  => $this->getPath(),
				'sourceMapFilename'                     => $this->getSourceMapFilename(),
				'devtoolFallbackModuleFilenameTemplate' => $this->getDevtoolFallbackModuleFilenameTemplate(),
				'devtoolModuleFilenameTemplate'         => $this->getDevtoolModuleFilenameTemplate(),
				'hashDigest'                            => $this->getHashDigest(),
				'hashDigestLength'                      => $this->getHashDigestLength(),
				'hashFunction'                          => $this->getHashFunction(),
				'hashSalt'                              => $this->getHashSalt(),
				'pathinfo'                              => $this->getPathinfo(),
				'publicPath'                            => $this->getPublicPath(),
				'sourcePrefix'                          => $this->getSourcePrefix(),
				'strictModuleExceptionHandling'         => $this->getStrictModuleExceptionHandling(),
				'umdNamedDefine'                        => $this->getUmdNamedDefine(),
			);
		}

	}
