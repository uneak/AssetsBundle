<?php

	namespace Uneak\AssetsBundle\Twig\Extension;

	use Uneak\AssetsBundle\AssetItem\Asset\Asset;
	use Uneak\AssetsBundle\Pool\AssetCollectionPoolItem;
	use Uneak\AssetsBundle\Pool\AssetPoolItem;
	use Uneak\AssetsBundle\Pool\AssetInclude;
	use Uneak\AssetsBundle\Pool\PoolFactoryInterface;

	class AssetsExtension extends \Twig_Extension {

		/**
		 * @var \Uneak\AssetsBundle\Pool\AssetInclude
		 */
		private $assetInclude;
		/**
		 * @var \Uneak\AssetsBundle\Pool\PoolFactoryInterface
		 */
		private $poolFactory;

		public function __construct(PoolFactoryInterface $poolFactory, AssetInclude $assetInclude) {
			$this->assetInclude = $assetInclude;
			$this->poolFactory = $poolFactory;
		}

		public function getFilters() {
			return array(
				new \Twig_SimpleFilter(
					'attr',
					array($this, 'attributesFilter'),
					array(
						'pre_escape' => 'html',
						'is_safe' => array('html'),
						'is_variadic' => true
					)
				),

				new \Twig_SimpleFilter(
					'js_array',
					array($this, 'jsArrayFilter'),
					array(
						'pre_escape' => 'html',
						'is_safe' => array('html'),
					)
				),

			);
		}


		public function getFunctions() {
			return array(
				new \Twig_SimpleFunction(
					'asset_include',
					array($this, 'includeAssetFunction'),
					array(
						'pre_escape' => 'html',
						'is_safe' => array('html'),
						'needs_environment' => true
					)
				),

				new \Twig_SimpleFunction(
					'set_asset_include',
					array($this, 'setIncludeAssetFunction')
				),

			);
		}


		public function setIncludeAssetFunction($include) {
			if (!$include instanceof AssetInclude) {
				foreach ((array)$include as $includeItem) {
					if (is_string($includeItem)) {
						$id = $includeItem;
						$data = null;
						$merge = true;
					} else {
						$id = $includeItem[0];
						$data = (isset($includeItem[1])) ? $includeItem[1] : null;
						$merge = (isset($includeItem[2])) ? $includeItem[2] : true;
					}

					$this->assetInclude->set($id, $data, $merge);
				}

			} else {
				foreach ($include->all() as $includeItem) {
					$id = $includeItem[0];
					$data = (isset($includeItem[1])) ? $includeItem[1] : null;
					$merge = (isset($includeItem[2])) ? $includeItem[2] : true;
					$this->assetInclude->set($id, $data, $merge);
				}
			}

		}

		public function includeAssetFunction(\Twig_Environment $twig, $section = null, $include = null) {
			if (is_null($include)) {
				$assetInclude = $this->assetInclude;

			} else if (!$include instanceof AssetInclude) {
				$assetInclude = new AssetInclude();
				foreach ((array)$include as $includeItem) {
					if (is_string($includeItem)) {
						$id = $includeItem;
						$data = null;
						$merge = true;
					} else {
						$id = $includeItem[0];
						$data = (isset($includeItem[1])) ? $includeItem[1] : null;
						$merge = (isset($includeItem[2])) ? $includeItem[2] : true;
					}

					$assetInclude->set($id, $data, $merge);
				}

			} else {
				$assetInclude = $include;
			}


			$pool = $this->poolFactory->getPool($assetInclude);
			$poolItems = $pool->all($section);
			$output = "";

			foreach ($poolItems as $poolItem) {
				if ($poolItem instanceof AssetPoolItem) {
					$output .= $this->renderAsset($twig, $poolItem->getData());

				} else if ($poolItem instanceof AssetCollectionPoolItem) {
					$assetCollection = $poolItem->getData();
					foreach ($assetCollection->all() as $asset) {
						$output .= $this->renderAsset($twig, $asset);
					}
				}
			}

			return $output;
		}

		private function renderAsset(\Twig_Environment $twig, Asset $asset) {
			$assetType = $this->poolFactory->getAssetType($asset);
			$output = $assetType->render($twig, $assetType->getRenderData($asset));
			return $output;
		}

		public function attributesFilter(array $options, array $params = array()) {

			if (count($params)) {
				$options = array_filter($options, function ($k) use ($params) {
					return in_array($k, $params);
				}, ARRAY_FILTER_USE_KEY);
			}

			$render = array();
			foreach ($options as $key => $value) {
				$render[] = sprintf('%s="%s"', $key, htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
			}
			
			return join(' ', $render);
		}


		public function jsArrayFilter(array $array = array(), $hashtagRaw = true) {
			$returnArray = array();
			foreach ($array as $key => $value) {
				if (!is_null($value)) {
					$returnArray[$key] = $value;
				}
			}
			$json = json_encode($returnArray);
			if ($hashtagRaw) {
				$json = preg_replace_callback("/(?:\"|')##(.*?)##(?:\"|')/", function ($matches) {
					return stripslashes($matches[1]);
				}, $json);
			}

			return $json;
		}


		public function getName() {
			return 'uneak_assets';
		}

	}
