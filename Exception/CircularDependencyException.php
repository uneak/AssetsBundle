<?php

	namespace Uneak\AssetsBundle\Exception;

	class CircularDependencyException extends \Exception implements ExceptionInterface {
		protected $nodes;
		protected $node;

		public function __construct($nodes, $node, $message = null) {
			
			if (!$message) {
				$path = implode(' -> ', $nodes);
				$message = sprintf('Circular dependency found: %s --@> %s', $path, $node);
			}
			
			parent::__construct($message, 0, null);

			$this->node = $node;
			$this->nodes = $nodes;

		}

		/**
		 * @return string
		 */
		public function getNode() {
			return $this->node;
		}

		/**
		 * @return mixed
		 */
		public function getNodes() {
			return $this->nodes;
		}
	}