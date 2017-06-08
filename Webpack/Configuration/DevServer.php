<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;


	use Uneak\AssetsBundle\Javascript\JsItem\JsAbstract;

	class DevServer extends JsAbstract {

		private $clientLogLevel;
		private $compress;
		private $contentBase;
		private $filename;
		private $headers;
		private $historyApiFallback;
		private $host;
		private $hot;
		private $hotOnly;
		private $https;
		private $inline;
		private $lazy;
		private $noInfo;
		private $overlay;
		private $port;
		private $proxy;
		private $progress;
		private $public;
		private $publicPath;
		private $quiet;
		private $setup;
		private $staticOptions;
		private $stats;
		private $watchContentBase;
		private $watchOptions;

		/**
		 * @return mixed
		 */
		public function getClientLogLevel() {
			return $this->clientLogLevel;
		}

		/**
		 * @param mixed $clientLogLevel
		 */
		public function setClientLogLevel($clientLogLevel) {
			$this->clientLogLevel = $clientLogLevel;
		}

		/**
		 * @return mixed
		 */
		public function getCompress() {
			return $this->compress;
		}

		/**
		 * @param mixed $compress
		 */
		public function setCompress($compress) {
			$this->compress = $compress;
		}

		/**
		 * @return mixed
		 */
		public function getContentBase() {
			return $this->contentBase;
		}

		/**
		 * @param mixed $contentBase
		 */
		public function setContentBase($contentBase) {
			$this->contentBase = $contentBase;
		}

		/**
		 * @return mixed
		 */
		public function getFilename() {
			return $this->filename;
		}

		/**
		 * @param mixed $filename
		 */
		public function setFilename($filename) {
			$this->filename = $filename;
		}

		/**
		 * @return mixed
		 */
		public function getHeaders() {
			return $this->headers;
		}

		/**
		 * @param mixed $headers
		 */
		public function setHeaders($headers) {
			$this->headers = $headers;
		}

		/**
		 * @return mixed
		 */
		public function getHistoryApiFallback() {
			return $this->historyApiFallback;
		}

		/**
		 * @param mixed $historyApiFallback
		 */
		public function setHistoryApiFallback($historyApiFallback) {
			$this->historyApiFallback = $historyApiFallback;
		}

		/**
		 * @return mixed
		 */
		public function getHost() {
			return $this->host;
		}

		/**
		 * @param mixed $host
		 */
		public function setHost($host) {
			$this->host = $host;
		}

		/**
		 * @return mixed
		 */
		public function getHot() {
			return $this->hot;
		}

		/**
		 * @param mixed $hot
		 */
		public function setHot($hot) {
			$this->hot = $hot;
		}

		/**
		 * @return mixed
		 */
		public function getHotOnly() {
			return $this->hotOnly;
		}

		/**
		 * @param mixed $hotOnly
		 */
		public function setHotOnly($hotOnly) {
			$this->hotOnly = $hotOnly;
		}

		/**
		 * @return mixed
		 */
		public function getHttps() {
			return $this->https;
		}

		/**
		 * @param mixed $https
		 */
		public function setHttps($https) {
			$this->https = $https;
		}

		/**
		 * @return mixed
		 */
		public function getInline() {
			return $this->inline;
		}

		/**
		 * @param mixed $inline
		 */
		public function setInline($inline) {
			$this->inline = $inline;
		}

		/**
		 * @return mixed
		 */
		public function getLazy() {
			return $this->lazy;
		}

		/**
		 * @param mixed $lazy
		 */
		public function setLazy($lazy) {
			$this->lazy = $lazy;
		}

		/**
		 * @return mixed
		 */
		public function getNoInfo() {
			return $this->noInfo;
		}

		/**
		 * @param mixed $noInfo
		 */
		public function setNoInfo($noInfo) {
			$this->noInfo = $noInfo;
		}

		/**
		 * @return mixed
		 */
		public function getOverlay() {
			return $this->overlay;
		}

		/**
		 * @param mixed $overlay
		 */
		public function setOverlay($overlay) {
			$this->overlay = $overlay;
		}

		/**
		 * @return mixed
		 */
		public function getPort() {
			return $this->port;
		}

		/**
		 * @param mixed $port
		 */
		public function setPort($port) {
			$this->port = $port;
		}

		/**
		 * @return mixed
		 */
		public function getProxy() {
			return $this->proxy;
		}

		/**
		 * @param mixed $proxy
		 */
		public function setProxy($proxy) {
			$this->proxy = $proxy;
		}

		/**
		 * @return mixed
		 */
		public function getProgress() {
			return $this->progress;
		}

		/**
		 * @param mixed $progress
		 */
		public function setProgress($progress) {
			$this->progress = $progress;
		}

		/**
		 * @return mixed
		 */
		public function getPublic() {
			return $this->public;
		}

		/**
		 * @param mixed $public
		 */
		public function setPublic($public) {
			$this->public = $public;
		}

		/**
		 * @return mixed
		 */
		public function getPublicPath() {
			return $this->publicPath;
		}

		/**
		 * @param mixed $publicPath
		 */
		public function setPublicPath($publicPath) {
			$this->publicPath = $publicPath;
		}

		/**
		 * @return mixed
		 */
		public function getQuiet() {
			return $this->quiet;
		}

		/**
		 * @param mixed $quiet
		 */
		public function setQuiet($quiet) {
			$this->quiet = $quiet;
		}

		/**
		 * @return mixed
		 */
		public function getSetup() {
			return $this->setup;
		}

		/**
		 * @param mixed $setup
		 */
		public function setSetup($setup) {
			$this->setup = $setup;
		}

		/**
		 * @return mixed
		 */
		public function getStaticOptions() {
			return $this->staticOptions;
		}

		/**
		 * @param mixed $staticOptions
		 */
		public function setStaticOptions($staticOptions) {
			$this->staticOptions = $staticOptions;
		}

		/**
		 * @return mixed
		 */
		public function getStats() {
			return $this->stats;
		}

		/**
		 * @param mixed $stats
		 */
		public function setStats($stats) {
			$this->stats = $stats;
		}

		/**
		 * @return mixed
		 */
		public function getWatchContentBase() {
			return $this->watchContentBase;
		}

		/**
		 * @param mixed $watchContentBase
		 */
		public function setWatchContentBase($watchContentBase) {
			$this->watchContentBase = $watchContentBase;
		}

		/**
		 * @return mixed
		 */
		public function getWatchOptions() {
			return $this->watchOptions;
		}

		/**
		 * @param mixed $watchOptions
		 */
		public function setWatchOptions($watchOptions) {
			$this->watchOptions = $watchOptions;
		}



		protected function _getData() {
			return array(
				'clientLogLevel'     => $this->getClientLogLevel(),
				'compress'           => $this->getCompress(),
				'contentBase'        => $this->getContentBase(),
				'filename'           => $this->getFilename(),
				'headers'            => $this->getHeaders(),
				'historyApiFallback' => $this->getHistoryApiFallback(),
				'host'               => $this->getHost(),
				'hot'                => $this->getHot(),
				'hotOnly'            => $this->getHotOnly(),
				'https'              => $this->getHttps(),
				'inline'             => $this->getInline(),
				'lazy'               => $this->getLazy(),
				'noInfo'             => $this->getNoInfo(),
				'overlay'            => $this->getOverlay(),
				'port'               => $this->getPort(),
				'proxy'              => $this->getProxy(),
				'progress'           => $this->getProgress(),
				'public'             => $this->getPublic(),
				'publicPath'         => $this->getPublicPath(),
				'quiet'              => $this->getQuiet(),
				'setup'              => $this->getSetup(),
				'staticOptions'      => $this->getStaticOptions(),
				'stats'              => $this->getStats(),
				'watchContentBase'   => $this->getWatchContentBase(),
				'watchOptions'       => $this->getWatchOptions(),
			);
		}
		
	}
