<?php

	namespace Uneak\AssetsBundle\Twig\Block;


	use Uneak\AssetsBundle\Block\BlockRenderer;

	class TwigRenderer extends BlockRenderer implements TwigRendererInterface {
		/**
		 * @var TwigRendererEngineInterface
		 */
		private $engine;

		public function __construct(TwigRendererEngineInterface $engine) {
			parent::__construct($engine);
			$this->engine = $engine;
		}

		/**
		 * {@inheritdoc}
		 */
		public function setEnvironment(\Twig_Environment $environment) {
			$this->engine->setEnvironment($environment);
		}
	}
