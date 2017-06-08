<?php

	namespace Uneak\AssetsBundle\Block\Util;

	/**
	 * Iterator that traverses an array of blocks.
	 *
	 * Contrary to \ArrayIterator, this iterator recognizes changes in the original
	 * array during iteration.
	 *
	 * You can wrap the iterator into a {@link \RecursiveIterator} in order to
	 * enter any child block that inherits its parent's data and iterate the children
	 * of that block as well.
	 *
	 */
	class InheritDataAwareIterator extends \IteratorIterator implements \RecursiveIterator {
		/**
		 * {@inheritdoc}
		 */
		public function getChildren() {
			return new static($this->current());
		}

		/**
		 * {@inheritdoc}
		 */
		public function hasChildren() {
			return (bool)$this->current()->getConfig()->getInheritData();
		}
	}
