<?php

	namespace Uneak\AssetsBundle\Pool;

	use Uneak\AssetsBundle\Exception\CircularDependencyException;

	class Pool {

		/**
		 * @var string[]
		 */
		protected $sections = array();
		/**
		 * @var PoolItemInterface[]
		 */
		protected $items = array();

		/**
		 * @param $id
		 *
		 * @return boolean
		 */
		public function has($id) {
			return isset($this->items[$id]);
		}

		/**
		 * @param $id
		 *
		 * @return PoolItemInterface
		 */
		public function get($id) {
			return $this->items[$id];
		}

		/**
		 * @param string            $id
		 * @param string|array      $section
		 * @param PoolItemInterface $poolItem
		 *
		 * @return $this
		 */
		public function set($id, $section, PoolItemInterface $poolItem) {
			$this->items[$id] = $poolItem;

			$sections = (array)$section;
			foreach ($sections as $section) {
				$this->sections[$section][$id] = $id;
			}

			return $this;
		}


		protected function visit(PoolItemInterface $poolItem, &$parents, &$items, &$sorted) {
			if (isset($parents[$poolItem->getId()])) {
				throw new CircularDependencyException($parents, $poolItem->getId());
			}

			// If element has not been visited
			if (!$poolItem->isVisited()) {
				$parents[$poolItem->getId()] = true;

				// Set that element has been visited
				$poolItem->setVisited(true);

				foreach ($poolItem->getDependencies() as $dependency) {
					if (isset($items[$dependency])) {
						$newParents = $parents;
						$this->visit($this->items[$dependency], $newParents, $items, $sorted);
					}
				}

				$sorted[$poolItem->getId()] = $poolItem;
			}
		}


		/**
		 * @param string|null $section
		 *
		 * @return PoolItemInterface[]
		 */
		public function all($section = null) {
			if (!$section) {
				$items = $this->items;
			} else if (isset($this->sections[$section])) {
				$items = array_intersect_key($this->items, $this->sections[$section]);
			} else {
				$items = array();
			}

			$sorted = array();
			foreach ($items as $item) {
				$parents = [];
				$this->visit($item, $parents, $items, $sorted);
			}

			return $sorted;

		}

	}