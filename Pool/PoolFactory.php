<?php

	namespace Uneak\AssetsBundle\Pool;

	use Symfony\Component\EventDispatcher\EventDispatcherInterface;
	use Uneak\AssetsBundle\AssetItem\Asset\Asset;
	use Uneak\AssetsBundle\AssetItem\AssetContainerInterface;
	use Uneak\AssetsBundle\AssetItem\Library\Library;
	use Uneak\AssetsBundle\Assets\Assets;
	use Uneak\AssetsBundle\AssetType\AssetTypeManager;
	use Uneak\AssetsBundle\Exception\CircularDependencyException;
	use Uneak\AssetsBundle\Exception\InvalidRenderDataException;
	use Uneak\AssetsBundle\Exception\NotFoundException;

	class PoolFactory implements PoolFactoryInterface {

		/**
		 * @var EventDispatcherInterface
		 */
		protected $eventDispatcher;
		/**
		 * @var Assets
		 */
		protected $assets;
		/**
		 * @var string[]
		 */
		protected $builded = array();
		/**
		 * @var \Uneak\AssetsBundle\AssetType\AssetTypeManager
		 */
		private $assetTypeManager;


		public function __construct(Assets $assets, AssetTypeManager $assetTypeManager, EventDispatcherInterface $eventDispatcher) {
			$this->eventDispatcher = $eventDispatcher;
			$this->assets = $assets;
			$this->assetTypeManager = $assetTypeManager;
		}


		/**
		 * @param AssetInclude $includes
		 *
		 * @return Pool
		 */
		public function getPool(AssetInclude $includes) {
			$visited = array();
			$tmpInclude = clone $includes;
			foreach ($tmpInclude as $include) {
				$id = $include['id'];
				if ($id instanceof AssetContainerInterface) {
					$this->_containerAssets($tmpInclude, $id, $include['data'], $visited);
				}
			}

			$pool = new Pool();
			foreach ($tmpInclude as $include) {
				$id = $include['id'];
				if (is_string($id)) {
					$this->_set($pool, $id, array(), $include['data'], $include['merge']);
				}
			}

			return $pool;
		}

		private function _containerAssets(AssetInclude $tmpInclude, AssetContainerInterface $container, $data, &$visited) {
			$hash = spl_object_hash($container);
			$container->assetInclude($tmpInclude, $this->assets, $data, isset($visited[$hash]));
			$visited[$hash] = $hash;
		}




		protected function _set(Pool $poolArray, $id, array $parents, $item = null, $merge = true) {
			if ($item && is_array($item) && !isset($item['id'])) {
				$item['id'] = $id;
			}

			//
			// essaie de recuperer le bulkItem
			// -> oui : met a jour id
			//
			try {
				$assetItem = $this->assets->getBulk()->find($id);
				$id = $assetItem->getId();
			} catch (NotFoundException $e) {
				$assetItem = null;
			}

			//
			// Si l'asset a deja été enregistré
			// -> si item : merge les datas
			// -> si !item : exit
			//
			if ($poolArray->has($id) && $merge) {
				if (null === $item = $poolArray->get($id)->merge($item)) {
					return array($id);
				}
			}

			//
			// verifie si dependance circulaire
			//
			if (in_array($id, $parents)) {
				throw new CircularDependencyException($parents, $id);
			}


			//
			// si item est un CollectionItem
			// -> clone pour pas le modifier per reference
			//
			//			if ($item instanceof CollectionItem) {
			//				$item = clone $item;
			//			}


			//
			// si il n'y a pas de item
			// -> initialise le avec bulkItem (si il existe)
			//
			if ($item === null || $merge) {
				if ($assetItem) {
					if ($item === null) {
						$item = $assetItem;
					} else if ($merge) {
						$assetItem->merge($item);
						$item = $assetItem;
					}
				}
			}

			//
			// Balance une erreur si il n'y a pas de item
			//
			if ($item === null) {
				//	TODO: lever une exception
				throw new InvalidRenderDataException("item invalide c null");
			}




			//
			// si il y a un bulkItem et que c'est une Library
			// -> parcours les dépendance
			// -> enregistre les main assets
			// -> exit
			//
			if ($item instanceof Library) {
				foreach ($item->getDependencies() as $dependency) {
					try {
						$this->_set($poolArray, $dependency, array_merge($parents, array($id)));
					} catch (InvalidRenderDataException $e) {
					}
				}

				$libraryMainAssets = array();
				foreach ($item->getMain() as $assetPath) {
					try {
						$libraryMainAssets = array_merge(
							$libraryMainAssets,
							$this->_set($poolArray, $assetPath, array_merge($parents, array($id)))
						);
					} catch (InvalidRenderDataException $e) {
					}
				}

				return array_unique($libraryMainAssets);
			}


			//
			// force item en Asset
			//
			if (!$item instanceof Asset) {

				// not an array
				if (!is_array($item)) {
					throw new InvalidRenderDataException('The item doit etre array.');
				}

				$item = $this->assetTypeManager->getAssetType($item['type'])->createAssetItem($id, null, $item);

				//				$item = new Asset($id, null, $item);
				//				$item = new Asset(
				//					$id,
				//					null,
				//					array(
				//						'path' => (isset($item['path'])) ? $item['path'] : null,
				//						'section' => (isset($item['section'])) ? $item['section'] : null,
				//						'parameters' => (isset($item['parameters'])) ? $item['parameters'] : array(),
				//						'type' => (isset($item['type'])) ? $item['type'] : null,
				//						'dependencies' => (isset($item['dependencies'])) ? $item['dependencies'] : array(),
				//						'tags' => (isset($item['tags'])) ? $item['tags'] : array(),
				//					)
				//				);

			}

			//
			// si l'asset fait partie d'une Library
			// -> enregistre les dépendences de la library
			//
			$libraryDependencies = array();
			if ($item->getParent()) {
				try {
					$library = $this->assets->getBulk()->find($item->getParent());
					if ($library instanceof Library) {
						foreach ($library->getDependencies() as $dependency) {
							try {
								$libraryDependencies = $this->_set($poolArray, $dependency, array_merge($parents, array($id)));
							} catch (InvalidRenderDataException $e) {
								$libraryDependencies[] = $dependency;
							}
						}
					}
				} catch (NotFoundException $e) {
				}
			}

			//
			// enregistre les dépendences de l'asset
			//
			$dependencies = array();
			foreach ($item->getDependencies() as $dependency) {
				try {
					$dependencies = $this->_set($poolArray, $dependency, array_merge($parents, array($id)));
				} catch (InvalidRenderDataException $e) {
					$dependencies[] = $dependency;
				}
			}

			$item->setDependencies(array_unique(array_merge(
				$libraryDependencies,
				$dependencies
			)));


			//
			$assetType = $this->getAssetType($item);
			$sections = array();
			if ($item->getSection()) {
				$sections[] = $item->getSection();
			}

			$assetType->buildPool($poolArray, $id, $item, $sections);
			//


			return array($id);
		}


		/**
		 * @param Asset $asset
		 *
		 * @return \Uneak\AssetsBundle\AssetType\AssetTypeInterface
		 */
		public function getAssetType(Asset $asset) {
			if (!$asset->getType()) {
				$guesser = $this->assetTypeManager->getTypeGuesser();
				$guess = $guesser->guessAsset($asset);
				if ($guess) {
					$asset->setType($guess->getType());
				} else {
					//	TODO: lever une exception
					throw new InvalidRenderDataException(sprintf("type %s invalide pour cette asset %s", $asset->getType(), $asset->getId()));
				}
			}

			return $this->assetTypeManager->getAssetType($asset->getType());
		}

	}