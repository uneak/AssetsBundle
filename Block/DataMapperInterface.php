<?php

	namespace Uneak\AssetsBundle\Block;

	interface DataMapperInterface {
		/**
		 * Maps properties of some data to a list of blocks.
		 *
		 * @param mixed           $data  Structured data
		 * @param BlockInterface[] $blocks A list of {@link BlockInterface} instances
		 *
		 * @throws Exception\UnexpectedTypeException if the type of the data parameter is not supported.
		 */
		public function mapDataToBlocks($data, $blocks);

	}
