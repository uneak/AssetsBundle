<?php

	namespace Uneak\AssetsBundle\Block;

	use Symfony\Component\EventDispatcher\Event;

	class BlockEvent extends Event {
		private $block;
		protected $data;

		/**
		 * Constructs an event.
		 *
		 * @param BlockInterface $block The associated block
		 * @param mixed          $data  The data
		 */
		public function __construct(BlockInterface $block, $data) {
			$this->block = $block;
			$this->data = $data;
		}

		/**
		 * Returns the block at the source of the event.
		 *
		 * @return BlockInterface
		 */
		public function getBlock() {
			return $this->block;
		}

		/**
		 * Returns the data associated with this event.
		 *
		 * @return mixed
		 */
		public function getData() {
			return $this->data;
		}

		/**
		 * Allows updating with some filtered data.
		 *
		 * @param mixed $data
		 */
		public function setData($data) {
			$this->data = $data;
		}
	}
