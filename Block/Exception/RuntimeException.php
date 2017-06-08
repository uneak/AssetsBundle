<?php

	namespace Uneak\AssetsBundle\Block\Exception;
	use Uneak\AssetsBundle\Exception\ExceptionInterface;

	/**
	 * Base RuntimeException for the Block component.
	 *
	 * @author Bernhard Schussek <bschussek@gmail.com>
	 */
	class RuntimeException extends \RuntimeException implements ExceptionInterface {
	}
