<?php

	namespace Uneak\AssetsBundle\AssetType\Guess;

	class TypeGuess extends Guess {
		/**
		 * The guessed asset type.
		 *
		 * @var string
		 */
		private $type;

		/**
		 * Constructor.
		 *
		 * @param string $type       The guessed field type
		 * @param int    $confidence The confidence that the guessed class name
		 *                           is correct
		 */
		public function __construct($type, $confidence) {
			parent::__construct($confidence);

			$this->type = $type;
		}

		/**
		 * Returns the guessed asset type.
		 *
		 * @return string
		 */
		public function getType() {
			return $this->type;
		}

	}
