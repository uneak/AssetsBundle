<?php

	namespace Uneak\AssetsBundle\AssetPackage;

	use Symfony\Component\Asset\Context\ContextInterface;
	use Symfony\Component\Asset\PathPackage;
	use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
	use Uneak\AssetsBundle\Assets\Assets;


	class AssetPackage extends PathPackage {

		/**
		 * @var Assets
		 */
		private $assets;

		public function __construct(Assets $assets, $basePath = "", ContextInterface $context = null) {
			parent::__construct($basePath, new EmptyVersionStrategy(), $context);
			$this->assets = $assets;
		}


		/**
		 * {@inheritdoc}
		 */
		public function getUrl($path) {
			if ($this->isAbsoluteUrl($path)) {
				return $path;
			}
			$path = $this->assets->getUrl($path);
			return parent::getUrl($path);
		}


	}