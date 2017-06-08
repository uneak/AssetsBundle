<?php

	namespace Uneak\AssetsBundle\Block;

	class ResolvedBlockTypeFactory implements ResolvedBlockTypeFactoryInterface {
		/**
		 * {@inheritdoc}
		 */
		public function createResolvedType(BlockTypeInterface $type, array $typeExtensions, ResolvedBlockTypeInterface $parent = null) {
			return new ResolvedBlockType($type, $typeExtensions, $parent);
		}
	}
