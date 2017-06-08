<?php

	namespace Uneak\AssetsBundle\Block\Exception;
	use Uneak\AssetsBundle\Exception\ExceptionInterface;

	/**
	 * Base BadMethodCallException for the Block component.
	 *
	 * @author Bernhard Schussek <bschussek@gmail.com>
	 */
	class BadMethodCallException extends \BadMethodCallException implements ExceptionInterface {
	}
