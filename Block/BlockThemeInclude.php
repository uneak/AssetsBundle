<?php

	namespace Uneak\AssetsBundle\Block;

	use Uneak\AssetsBundle\Exception\NotFoundException;

	class BlockThemeInclude implements \Iterator, \Serializable, \Countable {

		protected $position = 0;

		/**
		 * array[]
		 */
		protected $items = array();


		public function __construct(array $items = array()) {
			$this->items = $items;
		}

		/**
		 * @param $id
		 *
		 * @return boolean
		 */
		public function has($id) {
			foreach ($this->items as $item) {
				if ($item['id'] == $id) {
					return true;
				}
			}

			return false;
		}


		public function remove($id) {
			foreach ($this->items as &$item) {
				if ($item['id'] == $id) {
					unset($item);
				}
			}
		}


		/**
		 * @param $id
		 *
		 * @return array|null
		 * @throws \Uneak\AssetsBundle\Exception\NotFoundException
		 */
		public function get($id) {
			$found = null;
			foreach ($this->items as $item) {
				if ($item['id'] == $id) {
					$found = $item;
					break;
				}
			}
			if (!$found) {
				throw new NotFoundException(sprintf('Not Found %s', $id));
			}

			return $found;
		}

		/**
		 * @param mixed $id
		 * @param mixed $theme
		 *
		 * @return $this
		 */
		public function set($id, $theme) {
			$this->items[$id] = $theme;
			return $this;
		}

		public function all() {
			return $this->items;
		}

		/**
		 * {@inheritdoc}
		 */
		public function current() {
			return current($this->items);
		}

		/**
		 * {@inheritdoc}
		 */
		public function next() {
			next($this->items);
		}

		/**
		 * {@inheritdoc}
		 */
		public function key() {
			return key($this->items);
		}

		/**
		 * {@inheritdoc}
		 */
		public function valid() {
			return key($this->items) !== null;
		}

		/**
		 * {@inheritdoc}
		 */
		public function rewind() {
			reset($this->items);
		}

		/**
		 * {@inheritdoc}
		 */
		public function count() {
			return count($this->items);
		}

		/**
		 * {@inheritdoc}
		 */
		public function serialize() {
			return serialize(array(
				'items' => $this->items,
			));
		}

		/**
		 * {@inheritdoc}
		 */
		public function unserialize($serialized) {
			$data = unserialize($serialized);
			$this->items = $data['items'];
		}

	}