<?php

	namespace Uneak\AssetsBundle\Twig\Extension;
	use Uneak\AssetsBundle\Twig\Block\TwigRendererInterface;
	use Uneak\AssetsBundle\Twig\Node\RenderBlockNode;
	use Uneak\AssetsBundle\Twig\Node\SearchAndRenderBlockNode;
	use Uneak\AssetsBundle\Twig\TokenParser\BlockThemeTokenParser;

	/**
	 * BlockExtension extends Twig with block capabilities.
	 */
	class BlockExtension extends \Twig_Extension implements \Twig_Extension_InitRuntimeInterface {
		/**
		 * This property is public so that it can be accessed directly from compiled
		 * templates without having to call a getter, which slightly decreases performance.
		 *
		 * @var TwigRendererInterface
		 */
		public $renderer;

		public function __construct(TwigRendererInterface $renderer) {
			$this->renderer = $renderer;
		}

		/**
		 * {@inheritdoc}
		 */
		public function initRuntime(\Twig_Environment $environment) {
			$this->renderer->setEnvironment($environment);
		}

		/**
		 * {@inheritdoc}
		 */
		public function getTokenParsers() {
			return array(
				// {% block_theme block "SomeBundle::widgets.twig" %}
				new BlockThemeTokenParser(),
			);
		}

		/**
		 * {@inheritdoc}
		 */
		public function getFunctions() {
			return array(
				new \Twig_SimpleFunction('block_widget', null, array('node_class' => SearchAndRenderBlockNode::class, 'is_safe' => array('html'))),
				new \Twig_SimpleFunction('block_row', null, array('node_class' => SearchAndRenderBlockNode::class, 'is_safe' => array('html'))),
				new \Twig_SimpleFunction('block_rest', null, array('node_class' => SearchAndRenderBlockNode::class, 'is_safe' => array('html'))),
				new \Twig_SimpleFunction('block', null, array('node_class' => SearchAndRenderBlockNode::class, 'is_safe' => array('html'))),
			);
		}

		/**
		 * {@inheritdoc}
		 */
		public function getFilters() {
			return array(
				new \Twig_SimpleFilter('humanize', array($this, 'humanize')),
			);
		}


		/**
		 * Makes a technical name human readable.
		 *
		 * @param string $text The text to humanize
		 *
		 * @return string The humanized text
		 */
		public function humanize($text) {
			return $this->renderer->humanize($text);
		}

		/**
		 * {@inheritdoc}
		 */
		public function getName() {
			return 'block';
		}
	}
