<?php

	namespace Uneak\AssetsBundle\Finder;


	use Uneak\AssetsBundle\Exception\LinkNotFoundException;

	class FinderExtension implements FinderExtensionInterface, \Serializable{
		/**
		 * @var array[]
		 */
		private $links;
		
		public function __construct(array $links = array()) {
			$this->links = $links;
		}

		public function path($key) {
			$alias = $this->find($key);
			if (!isset($this->links[$alias]['path'])) {
				throw new LinkNotFoundException(sprintf("path %s not found", $key));
			}
			return $this->links[$alias]['path'] . substr($key, strlen($alias));
		}

		public function file($key) {
			$alias = $this->find($key);
			if (!isset($this->links[$alias]['file'])) {
				throw new LinkNotFoundException(sprintf("file %s not found", $key));
			}
			return $this->links[$alias]['file'] . substr($key, strlen($alias));
		}

		public function find($key) {
			foreach ($this->links as $alias => $link) {
				if ($alias === substr($key, 0, strlen($alias))) {
					return $alias;
				}
			}
			throw new LinkNotFoundException(sprintf("lien %s not found", $key));
		}
		
		public function all() {
			return $this->links;
		}

		public function has($key) {
			foreach ($this->links as $alias => $link) {
				if ($alias === substr($key, 0, strlen($alias))) {
					return true;
				}
			}
			return false;
		}

		/**
		 * {@inheritdoc}
		 */
		public function serialize() {
			return serialize($this->links);
		}

		/**
		 * {@inheritdoc}
		 */
		public function unserialize($serialized) {
			$this->links = unserialize($serialized);
		}
		
		
	}