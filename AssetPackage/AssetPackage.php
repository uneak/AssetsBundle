<?php

	namespace Uneak\AssetsBundle\AssetPackage;

	use Symfony\Component\Asset\Context\ContextInterface;
	use Symfony\Component\Asset\PathPackage;
	use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
	use Uneak\AssetsBundle\Assets\Assets;
	use Uneak\AssetsBundle\Finder\Finder;
	use Uneak\AssetsBundle\Finder\FinderExtensionInterface;


	class AssetPackage extends PathPackage {

		/**
		 * @var FinderExtensionInterface
		 */
		private $finder;

		public function __construct(FinderExtensionInterface $finder, $basePath = "", ContextInterface $context = null) {
			parent::__construct($basePath, new EmptyVersionStrategy(), $context);
			$this->finder = $finder;
		}


		/**
		 * {@inheritdoc}
		 */
		public function getUrl($path) {
			if ($this->isAbsoluteUrl($path)) {
				return $path;
			}
			$path = $this->finder->path($path);
			return parent::getUrl($path);
		}


	}