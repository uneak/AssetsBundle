<?php

	namespace Uneak\AssetsBundle\Block;

	final class BlockEvents {

		/**
		 * The BlockEvents::PRE_SET_DATA event is dispatched at the beginning of the Block::setData() method.
		 *
		 * It can be used to:
		 *  - Modify the data given during pre-population;
		 *  - Modify a block depending on the pre-populated data (adding or removing fields dynamically).
		 * The event listener method receives a Uneak\AssetsBundle\Block\BlockEvent instance.
		 *
		 * @Event
		 */
		const PRE_SET_DATA = 'block.pre_set_data';

		/**
		 * The BlockEvents::POST_SET_DATA event is dispatched at the end of the Block::setData() method.
		 *
		 * This event is mostly here for reading data after having pre-populated the block.
		 * The event listener method receives a Uneak\AssetsBundle\Block\BlockEvent instance.
		 *
		 * @Event
		 */
		const POST_SET_DATA = 'block.post_set_data';

		private function __construct() {
		}
	}
