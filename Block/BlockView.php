<?php


	namespace Uneak\AssetsBundle\Block;

	use Uneak\AssetsBundle\Block\Exception\BadMethodCallException;

	class BlockView implements \ArrayAccess, \IteratorAggregate, \Countable {
		/**
		 * The variables assigned to this view.
		 *
		 * @var array
		 */
		public $vars = array(
			'value' => null,
			'attr'  => array(),
		);

		/**
		 * The parent view.
		 *
		 * @var BlockView
		 */
		public $parent;

		/**
		 * The child views.
		 *
		 * @var BlockView[]
		 */
		public $children = array();

		/**
		 * Is the block attached to this renderer rendered?
		 *
		 * Rendering happens when either the widget or the row method was called.
		 * Row implicitly includes widget, however certain rendering mechanisms
		 * have to skip widget rendering when a row is rendered.
		 *
		 * @var bool
		 */
		private $rendered = false;

		public function __construct(BlockView $parent = null) {
			$this->parent = $parent;
		}

		/**
		 * Returns whether the view was already rendered.
		 *
		 * @return bool Whether this view's widget is rendered
		 */
		public function isRendered() {
			$hasChildren = 0 < count($this->children);

			if (true === $this->rendered || !$hasChildren) {
				return $this->rendered;
			}

			if ($hasChildren) {
				foreach ($this->children as $child) {
					if (!$child->isRendered()) {
						return false;
					}
				}

				return $this->rendered = true;
			}

			return false;
		}

		/**
		 * Marks the view as rendered.
		 *
		 * @return BlockView The view object
		 */
		public function setRendered() {
			$this->rendered = true;

			return $this;
		}

		/**
		 * Returns a child by name (implements \ArrayAccess).
		 *
		 * @param string $name The child name
		 *
		 * @return BlockView The child view
		 */
		public function offsetGet($name) {
			return $this->children[$name];
		}

		/**
		 * Returns whether the given child exists (implements \ArrayAccess).
		 *
		 * @param string $name The child name
		 *
		 * @return bool Whether the child view exists
		 */
		public function offsetExists($name) {
			return isset($this->children[$name]);
		}

		/**
		 * Implements \ArrayAccess.
		 *
		 * @throws BadMethodCallException always as setting a child by name is not allowed
		 */
		public function offsetSet($name, $value) {
			throw new BadMethodCallException('Not supported');
		}

		/**
		 * Removes a child (implements \ArrayAccess).
		 *
		 * @param string $name The child name
		 */
		public function offsetUnset($name) {
			unset($this->children[$name]);
		}

		/**
		 * Returns an iterator to iterate over children (implements \IteratorAggregate).
		 *
		 * @return \ArrayIterator|BlockView[] The iterator
		 */
		public function getIterator() {
			return new \ArrayIterator($this->children);
		}

		/**
		 * Implements \Countable.
		 *
		 * @return int The number of children views
		 */
		public function count() {
			return count($this->children);
		}
	}
