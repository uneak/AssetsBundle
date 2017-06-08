<?php

	namespace Uneak\AssetsBundle\Twig\Block;

	use Uneak\AssetsBundle\Block\BlockRendererEngineInterface;

	interface TwigRendererEngineInterface extends BlockRendererEngineInterface {
		/**
		 * Sets Twig's environment.
		 *
		 * @param \Twig_Environment $environment
		 */
		public function setEnvironment(\Twig_Environment $environment);
	}
