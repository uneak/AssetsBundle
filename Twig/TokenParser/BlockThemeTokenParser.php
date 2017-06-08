<?php


	namespace Uneak\AssetsBundle\Twig\TokenParser;
	use Uneak\AssetsBundle\Twig\Node\BlockThemeNode;


	/**
	 * Token Parser for the 'block_theme' tag.
	 *
	 */
	class BlockThemeTokenParser extends \Twig_TokenParser {
		/**
		 * Parses a token and returns a node.
		 *
		 * @param \Twig_Token $token A Twig_Token instance
		 *
		 * @return \Twig_Node A Twig_Node instance
		 */
		public function parse(\Twig_Token $token) {
			$lineno = $token->getLine();
			$stream = $this->parser->getStream();

			$block = $this->parser->getExpressionParser()->parseExpression();

			if ($this->parser->getStream()->test(\Twig_Token::NAME_TYPE, 'with')) {
				$this->parser->getStream()->next();
				$resources = $this->parser->getExpressionParser()->parseExpression();
			} else {
				$resources = new \Twig_Node_Expression_Array(array(), $stream->getCurrent()->getLine());
				do {
					$resources->addElement($this->parser->getExpressionParser()->parseExpression());
				} while (!$stream->test(\Twig_Token::BLOCK_END_TYPE));
			}

			$stream->expect(\Twig_Token::BLOCK_END_TYPE);

			return new BlockThemeNode($block, $resources, $lineno, $this->getTag());
		}

		/**
		 * Gets the tag name associated with this token parser.
		 *
		 * @return string The tag name
		 */
		public function getTag() {
			return 'block_theme';
		}
	}
