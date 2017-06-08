<?php

	namespace Uneak\AssetsBundle\Block;

	/**
	 * Creates ResolvedBlockTypeInterface instances.
	 *
	 * This interface allows you to use your custom ResolvedBlockTypeInterface
	 * implementation, within which you can customize the concrete BlockBuilderInterface
	 * implementations or BlockView subclasses that are used by the framework.
	 *
	 */
	interface ResolvedBlockTypeFactoryInterface {
		/**
		 * Resolves a block type.
		 *
		 * @param BlockTypeInterface              $type
		 * @param BlockTypeExtensionInterface[]   $typeExtensions
		 * @param ResolvedBlockTypeInterface|null $parent
		 *
		 * @return ResolvedBlockTypeInterface
		 *
		 * @throws Exception\UnexpectedTypeException  if the types parent {@link BlockTypeInterface::getParent()} is not
		 *                                            a string
		 * @throws Exception\InvalidArgumentException if the types parent can not be retrieved from any extension
		 */
		public function createResolvedType(BlockTypeInterface $type, array $typeExtensions, ResolvedBlockTypeInterface $parent = null);
	}
