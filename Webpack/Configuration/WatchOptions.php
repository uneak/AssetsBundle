<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;


	use Uneak\AssetsBundle\Javascript\JsItem\JsAbstract;

	class WatchOptions extends JsAbstract implements ExtraInterface {

		use ExtraTrait;

		private $aggregateTimeout;
		private $ignored;
		private $poll;

		/**
		 * @return mixed
		 */
		public function getAggregateTimeout() {
			return $this->aggregateTimeout;
		}

		/**
		 * @param mixed $aggregateTimeout
		 *
		 * @return WatchOptions
		 */
		public function setAggregateTimeout($aggregateTimeout) {
			$this->aggregateTimeout = $aggregateTimeout;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getIgnored() {
			return $this->ignored;
		}

		/**
		 * @param mixed $ignored
		 *
		 * @return WatchOptions
		 */
		public function setIgnored($ignored) {
			$this->ignored = $ignored;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getPoll() {
			return $this->poll;
		}

		/**
		 * @param mixed $poll
		 *
		 * @return WatchOptions
		 */
		public function setPoll($poll) {
			$this->poll = $poll;

			return $this;
		}

		
		protected function _getData() {
			return array(
				'aggregateTimeout' => $this->getAggregateTimeout(),
				'ignored'          => $this->getIgnored(),
				'poll'             => $this->getPoll(),

			);

		}
	}
