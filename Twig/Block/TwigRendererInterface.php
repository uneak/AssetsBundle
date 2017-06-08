<?php

	/*
	 * This file is part of the Symfony package.
	 *
	 * (c) Fabien Potencier <fabien@symfony.com>
	 *
	 * For the full copyright and license information, please view the LICENSE
	 * file that was distributed with this source code.
	 */

	namespace Uneak\AssetsBundle\Twig\Block;

	use Uneak\AssetsBundle\Block\BlockRendererInterface;

	interface TwigRendererInterface extends BlockRendererInterface {
		/**
		 * Sets Twig's environment.
		 *
		 * @param \Twig_Environment $environment
		 */
		public function setEnvironment(\Twig_Environment $environment);
	}
