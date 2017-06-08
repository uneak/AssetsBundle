<?php

	namespace Uneak\AssetsBundle\Twig\Node;

	class BlockThemeNode extends \Twig_Node {
		public function __construct(\Twig_Node $block, \Twig_Node $resources, $lineno, $tag = null) {
			parent::__construct(array('block' => $block, 'resources' => $resources), array(), $lineno, $tag);
		}

		/**
		 * Compiles the node to PHP.
		 *
		 * @param \Twig_Compiler $compiler A Twig_Compiler instance
		 */
		public function compile(\Twig_Compiler $compiler) {
			$compiler
				->addDebugInfo($this)
				->write('$this->env->getExtension(\'block\')->renderer->setTheme(')
				->subcompile($this->getNode('block'))
				->raw(', ')
				->subcompile($this->getNode('resources'))
				->raw(");\n");
		}
	}
