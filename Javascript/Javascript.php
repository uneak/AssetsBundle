<?php

	namespace Uneak\AssetsBundle\Javascript;

	use Uneak\AssetsBundle\Javascript\JsItem\JsArrayExtend;
	use Uneak\AssetsBundle\Javascript\JsItem\JsItemInterface;

	class Javascript {

		static public function encode($var) {
			switch (gettype($var)) {
				case 'boolean':
					return $var ? 'true' : 'false';

				case 'NULL':
					return 'null';

				case 'integer':
					return (int)$var;

				case 'double':
				case 'float':
					return (float)$var;

				case 'string':
					// STRINGS ARE EXPECTED TO BE IN ASCII OR UTF-8 FORMAT
					$ascii = '';
					$strlen_var = strlen($var);

					/*
					 * Iterate over every character in the string,
					 * escaping with a slash or encoding to UTF-8 where necessary
					 */
					for ($c = 0; $c < $strlen_var; ++$c) {

						$ord_var_c = ord($var{$c});

						switch (true) {
							case $ord_var_c == 0x08:
								$ascii .= '\b';
								break;
							case $ord_var_c == 0x09:
								$ascii .= '\t';
								break;
							case $ord_var_c == 0x0A:
								$ascii .= '\n';
								break;
							case $ord_var_c == 0x0C:
								$ascii .= '\f';
								break;
							case $ord_var_c == 0x0D:
								$ascii .= '\r';
								break;

							case $ord_var_c == 0x22:
//							case $ord_var_c == 0x2F:
							case $ord_var_c == 0x5C:
								// double quote, slash, slosh
								$ascii .= '\\' . $var{$c};
								break;

							case (($ord_var_c >= 0x20) && ($ord_var_c <= 0x7F)):
								// characters U-00000000 - U-0000007F (same as ASCII)
								$ascii .= $var{$c};
								break;

							case (($ord_var_c & 0xE0) == 0xC0):
								// characters U-00000080 - U-000007FF, mask 110XXXXX
								// see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
								$char = pack('C*', $ord_var_c, ord($var{$c + 1}));
								$c += 1;
								$utf16 = Javascript::utf82utf16($char);
								$ascii .= sprintf('\u%04s', bin2hex($utf16));
								break;

							case (($ord_var_c & 0xF0) == 0xE0):
								// characters U-00000800 - U-0000FFFF, mask 1110XXXX
								// see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
								$char = pack('C*', $ord_var_c,
									ord($var{$c + 1}),
									ord($var{$c + 2}));
								$c += 2;
								$utf16 = Javascript::utf82utf16($char);
								$ascii .= sprintf('\u%04s', bin2hex($utf16));
								break;

							case (($ord_var_c & 0xF8) == 0xF0):
								// characters U-00010000 - U-001FFFFF, mask 11110XXX
								// see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
								$char = pack('C*', $ord_var_c,
									ord($var{$c + 1}),
									ord($var{$c + 2}),
									ord($var{$c + 3}));
								$c += 3;
								$utf16 = Javascript::utf82utf16($char);
								$ascii .= sprintf('\u%04s', bin2hex($utf16));
								break;

							case (($ord_var_c & 0xFC) == 0xF8):
								// characters U-00200000 - U-03FFFFFF, mask 111110XX
								// see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
								$char = pack('C*', $ord_var_c,
									ord($var{$c + 1}),
									ord($var{$c + 2}),
									ord($var{$c + 3}),
									ord($var{$c + 4}));
								$c += 4;
								$utf16 = Javascript::utf82utf16($char);
								$ascii .= sprintf('\u%04s', bin2hex($utf16));
								break;

							case (($ord_var_c & 0xFE) == 0xFC):
								// characters U-04000000 - U-7FFFFFFF, mask 1111110X
								// see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
								$char = pack('C*', $ord_var_c,
									ord($var{$c + 1}),
									ord($var{$c + 2}),
									ord($var{$c + 3}),
									ord($var{$c + 4}),
									ord($var{$c + 5}));
								$c += 5;
								$utf16 = Javascript::utf82utf16($char);
								$ascii .= sprintf('\u%04s', bin2hex($utf16));
								break;
						}
					}

					return '"' . $ascii . '"';

				case 'array':
					// key => value
					if (is_array($var) && count($var) && (array_keys($var) !== range(0, sizeof($var) - 1))) {

						array_walk($var, function (&$value, $key) {
							$return = Javascript::encode($value);
							if (!$value instanceof JsArrayExtend) {
								$return = sprintf("%s: ", strval($key)) . $return;
							}

							$value = $return;
						});


						$strReturn = '{';
						if (count($var)) {
							if (count($var) == 1) {
								$strReturn .= join(',', $var);
							} else {
								$strReturn .= PHP_EOL . join(',' . PHP_EOL, $var);
								$strReturn = join(PHP_EOL . "\t", explode(PHP_EOL, $strReturn));
								$strReturn .= PHP_EOL;
							}
						}
						$strReturn .= '}';

					} else {

						// x => value
						foreach ($var as &$value) {
							$value = Javascript::encode($value);
						}

						$strReturn = '[';
						if (count($var)) {
							if (count($var) == 1) {
								$strReturn .= join(',', $var);
							} else {
								$strReturn .= PHP_EOL . join(',' . PHP_EOL, $var);
								$strReturn = join(PHP_EOL . "\t", explode(PHP_EOL, $strReturn));
								$strReturn .= PHP_EOL;
							}
						}
						$strReturn .= ']';

					}

					return $strReturn;

				case 'object':

					if ($var instanceof JsItemInterface) {
						$strReturn = $var->jsRender();

					} else {
						$vars = get_object_vars($var);

						array_walk($vars, function (&$value, $key) {
							$return = Javascript::encode($value);
							if (!$value instanceof JsArrayExtend) {
								$return = sprintf("%s: ", strval($key)) . $return;
							}

							$value = $return;
						});

						$strReturn = '{';
						if (count($vars)) {
							if (count($vars) == 1) {
								$strReturn .= join(',', $vars);
							} else {
								$strReturn .= PHP_EOL . join(',' . PHP_EOL, $vars);
								$strReturn = join(PHP_EOL . "\t", explode(PHP_EOL, $strReturn));
								$strReturn .= PHP_EOL;
							}

						}
						$strReturn .= '}';

					}

					return $strReturn;

				default:
					return 'null';
			}
		}


		/**
		 * convert a string from one UTF-8 char to one UTF-16 char
		 *
		 * Normally should be handled by mb_convert_encoding, but
		 * provides a slower PHP-only method for installations
		 * that lack the multibye string extension.
		 *
		 * @param    string $utf8 UTF-8 character
		 *
		 * @return   string  UTF-16 character
		 */
		static public function utf82utf16($utf8) {
			// oh please oh please oh please oh please oh please
			if (function_exists('mb_convert_encoding')) {
				return mb_convert_encoding($utf8, 'UTF-16', 'UTF-8');
			}

			switch (strlen($utf8)) {
				case 1:
					// this case should never be reached, because we are in ASCII range
					// see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
					return $utf8;

				case 2:
					// return a UTF-16 character from a 2-byte UTF-8 char
					// see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
					return chr(0x07 & (ord($utf8{0}) >> 2))
					. chr((0xC0 & (ord($utf8{0}) << 6))
						| (0x3F & ord($utf8{1})));

				case 3:
					// return a UTF-16 character from a 3-byte UTF-8 char
					// see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
					return chr((0xF0 & (ord($utf8{0}) << 4))
						| (0x0F & (ord($utf8{1}) >> 2)))
					. chr((0xC0 & (ord($utf8{1}) << 6))
						| (0x7F & ord($utf8{2})));
			}

			// ignoring UTF-32 for now, sorry
			return '';
		}

	}