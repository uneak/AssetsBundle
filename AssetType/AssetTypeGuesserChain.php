<?php

	namespace Uneak\AssetsBundle\AssetType;

	use Symfony\Component\Form\Exception\UnexpectedTypeException;
	use Uneak\AssetsBundle\AssetItem\Asset\Asset;
	use Uneak\AssetsBundle\AssetType\Guess\Guess;

	class AssetTypeGuesserChain implements AssetTypeGuesserInterface {
		private $guessers = array();

		/**
		 * Constructor.
		 *
		 * @param AssetTypeGuesserInterface[] $guessers Guessers as instances of AssetTypeGuesserInterface
		 *
		 * @throws UnexpectedTypeException if any guesser does not implement AssetTypeGuesserInterface
		 */
		public function __construct(array $guessers) {
			foreach ($guessers as $guesser) {
				if (!$guesser instanceof AssetTypeGuesserInterface) {
					throw new UnexpectedTypeException($guesser, 'Uneak\AssetsBundle\AssetType\AssetTypeGuesserInterface');
				}

				if ($guesser instanceof self) {
					$this->guessers = array_merge($this->guessers, $guesser->guessers);
				} else {
					$this->guessers[] = $guesser;
				}
			}
		}

		/**
		 * {@inheritdoc}
		 */
		public function guessAsset(Asset $asset) {
			return $this->guess(function ($guesser) use ($asset) {
				return $guesser->guessAsset($asset);
			});
		}



		/**
		 * Executes a closure for each guesser and returns the best guess from the
		 * return values.
		 *
		 * @param \Closure $closure The closure to execute. Accepts a guesser
		 *                          as argument and should return a Guess instance
		 *
		 * @return Guess|null The guess with the highest confidence
		 */
		private function guess(\Closure $closure) {
			$guesses = array();

			foreach ($this->guessers as $guesser) {
				if ($guess = $closure($guesser)) {
					$guesses[] = $guess;
				}
			}

			return Guess::getBestGuess($guesses);
		}


	}
