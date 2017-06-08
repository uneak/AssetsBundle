<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;


	trait ContextTrait {
		/**
		 * @var string
		 */
		private $context;

		/**
		 * @return string
		 */
		public function getContext() {
			return $this->context;
		}

		/**
		 * @param string $context
		 *
		 * @return $this
		 */
		public function setContext($context) {
			$this->context = $context;
			return $this;
		}

		
	}
