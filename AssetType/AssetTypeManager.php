<?php

	namespace Uneak\AssetsBundle\AssetType;

	use Uneak\AssetsBundle\Exception\InvalidAssetTypeException;

	class AssetTypeManager {

		/**
		 * @var AssetTypeInterface[]
		 */
		protected $assetTypes = array();

		/**
		 * @var array
		 */
		protected $typeGuesser = array();
		/**
		 * @var null|AssetTypeGuesserInterface
		 */
		protected $guesser = null;


		/**
		 * @return AssetTypeInterface[]
		 */
		public function getAssetTypes() {
			return $this->assetTypes;
		}

		/**
		 * @param string $alias
		 *
		 * @return AssetTypeInterface
		 */
		public function getAssetType($alias) {
			if (!isset($this->assetTypes[$alias])) {
				throw new InvalidAssetTypeException(sprintf("AssetType %s not found", $alias));
			}

			return $this->assetTypes[$alias];
		}


		/**
		 * @param AssetTypeInterface $assetType
		 *
		 * @return $this
		 */
		public function setAssetType(AssetTypeInterface $assetType) {
			$this->assetTypes[$assetType->getAlias()] = $assetType;

			return $this;
		}

		/**
		 * @param string $alias
		 *
		 * @return bool
		 */
		public function hasAssetType($alias) {
			return isset($this->assetTypes[$alias]);
		}

		/**
		 * @param string $alias
		 *
		 * @return $this
		 */
		public function removeAssetType($alias) {
			unset($this->assetTypes[$alias]);

			return $this;
		}


		public function addTypeGuesser(AssetTypeGuesserInterface $typeGuesser) {
			$this->typeGuesser[] = $typeGuesser;
			return $this;
		}

		/**
		 * @return null|AssetTypeGuesserInterface
		 */
		public function getTypeGuesser() {
			if (null === $this->guesser) {
				$guessers = $this->typeGuesser;

				foreach ($this->assetTypes as $assetType) {
					$guesser = $assetType->getTypeGuesser();
					if ($guesser) {
						foreach ((array)$guesser as $item) {
							$guessers[] = $item;
						}
					}
				}

				$this->guesser = !empty($guessers) ? new AssetTypeGuesserChain($guessers) : null;
			}

			return $this->guesser;
		}


	}