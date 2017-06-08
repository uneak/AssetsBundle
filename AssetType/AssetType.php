<?php

	namespace Uneak\AssetsBundle\AssetType;

	use Symfony\Component\Config\Definition\Builder\NodeDefinition;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Uneak\AssetsBundle\AssetItem\Asset\Asset;
	use Uneak\AssetsBundle\Pool\Pool;


	abstract class AssetType implements AssetTypeInterface {

		/**
		 * @var OptionsResolver
		 */
		protected $resolver;

		public function __construct() {
			$this->resolver = new OptionsResolver();
			$this->configureOptions($this->resolver);
		}

		protected function resolve(array $options) {
			return $this->resolver->resolve($options);
		}

		public function getTypeGuesser() {
			return null;
		}

		abstract public function buildPool(Pool $pool, $id, $data, array $sections = array());
		abstract protected function configureOptions(OptionsResolver $resolver);
		abstract public function getRenderData($data);
		abstract public function render(\Twig_Environment $twig, $data);

		abstract public function addExtraConfiguration(NodeDefinition $node);
		public function createAssetItem($name, $parent = null, array $options = array()) {
			return new Asset($name, $parent, $options);
		}
		
	}