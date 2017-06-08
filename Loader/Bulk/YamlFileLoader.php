<?php

	namespace Uneak\AssetsBundle\Loader\Bulk;

	use Symfony\Component\Config\Definition\Builder\TreeBuilder;
	use Symfony\Component\Config\Definition\Processor;
	use Symfony\Component\Config\Resource\FileResource;
	use Symfony\Component\Yaml\Exception\ParseException;
	use Symfony\Component\Yaml\Parser as YamlParser;
	use Uneak\AssetsBundle\AssetItem\Bulk\Bulk;
	use Uneak\AssetsBundle\AssetType\AssetTypeManager;
	use Uneak\AssetsBundle\Config\AssetsConfiguration;
	use Uneak\AssetsBundle\Config\BulkConfiguration;
	use Uneak\AssetsBundle\Config\LibrariesConfiguration;
	use Uneak\AssetsBundle\LibraryType\LibraryTypeManager;
	use Uneak\AssetsBundle\Loader\FileLoader;


	class YamlFileLoader extends FileLoader {


		CONST CONFIGURATIONS = array(
			'bulk'      => BulkConfiguration::class,
			'libraries' => LibrariesConfiguration::class,
			'assets'    => AssetsConfiguration::class,
		);
		
		private $yamlParser;
		/**
		 * @var \Uneak\AssetsBundle\LibraryType\LibraryTypeManager
		 */
		private $libraryTypeManager;
		/**
		 * @var \Uneak\AssetsBundle\AssetType\AssetTypeManager
		 */
		private $assetTypeManager;


		public function __construct(\Symfony\Component\Config\FileLocatorInterface $locator, LibraryTypeManager $libraryTypeManager, AssetTypeManager $assetTypeManager) {
			parent::__construct($locator);
			$this->libraryTypeManager = $libraryTypeManager;
			$this->assetTypeManager = $assetTypeManager;
		}

		public function load($file, $type = null, $parent = null) {
			$path = $this->locator->locate($file);

			if (!stream_is_local($path)) {
				throw new \InvalidArgumentException(sprintf('This is not a local file "%s".', $path));
			}

			if (!file_exists($path)) {
				throw new \InvalidArgumentException(sprintf('File "%s" not found.', $path));
			}

			if (null === $this->yamlParser) {
				$this->yamlParser = new YamlParser();
			}

			try {
				$parsedConfig = $this->yamlParser->parse(file_get_contents($path));
			} catch (ParseException $e) {
				throw new \InvalidArgumentException(sprintf('The file "%s" does not contain valid YAML.', $path), 0, $e);
			}

			$bulk = new Bulk("temp");
			$bulk->addResource(new FileResource($path));

			// empty file
			if (null === $parsedConfig) {
				return $bulk;
			}

			// not an array
			if (!is_array($parsedConfig)) {
				throw new \InvalidArgumentException(sprintf('The file "%s" must contain a YAML array.', $path));
			}

			call_user_func(array($this, $type), $bulk, $parent, $path, $file, $this->parseConfiguration($type, $parsedConfig));

			return $bulk;

		}

		protected function parseConfiguration($type, $parsedConfig) {
			$configurationClass = self::CONFIGURATIONS[$type];
			$processor = new Processor();
			return $processor->processConfiguration(new $configurationClass(), array($type => $parsedConfig));
		}

		public function bulk(Bulk $bulk, $parent, $path, $file, $configuration) {

			if (isset($configuration['tag'])) {
				$parent['tags'][] = $configuration['tag'];
				$parent['tags'] = array_unique($parent['tags']);
			}

			if (isset($configuration['packages'])) {
				foreach ($configuration['packages'] as $name => $config) {
					if (isset($config['resource'])) {
						$this->importBulk($bulk, $parent, $config, $path, $file);
					}
				}
			}
			if (isset($configuration['libraries'])) {
				$this->libraries($bulk, $parent, $path, $file, $configuration['libraries']);
			}
			if (isset($configuration['assets'])) {
				$this->assets($bulk, $parent, $path, $file, $configuration['assets']);
			}
		}

		public function libraries(Bulk $bulk, $parent, $path, $file, $configuration) {
			foreach ($configuration as $name => $config) {
				if (isset($config['resource'])) {
					$this->importLibraries($bulk, $parent, $config, $path, $file);
				} else {
					$this->parseLibrary($bulk, $parent, $name, $config, $path, $file);
				}
			}
		}

		public function assets(Bulk $bulk, $parent, $path, $file, $configuration) {
			foreach ($configuration as $name => $config) {
				if (isset($config['resource'])) {
					$this->importAssets($bulk, $parent, $config, $path, $file);
				} else {
					$this->parseAsset($bulk, $parent, $name, $config);
				}
			}
		}


		private function importBulk(Bulk $bulk, $parent, array $config, $path, $file) {
			$this->setCurrentDir(dirname($path));
			$subBulk = $this->import($config['resource'], 'bulk', $parent, false, $file);
			$bulk->merge($subBulk);
		}

		private function importLibraries(Bulk $bulk, $parent, array $config, $path, $file) {
			$this->setCurrentDir(dirname($path));
			$subBulk = $this->import($config['resource'], 'libraries', $parent, false, $file);
			$bulk->merge($subBulk);
		}

		private function importAssets(Bulk $bulk, $parent, array $config, $path, $file) {
			$this->setCurrentDir(dirname($path));
			$subBulk = $this->import($config['resource'], 'assets', $parent, false, $file);
			$bulk->merge($subBulk);
		}


		private function parseLibrary(Bulk $bulk, $parent, $name, array $config, $path, $file) {

			$treeBuilder = new TreeBuilder();
			$node = $treeBuilder->root('item');
			LibrariesConfiguration::addLibraryConfiguration($node);

			$definition = $this->libraryTypeManager->getLibraryType($config['type']);
			$definition->addExtraConfiguration($node);

			$processor = new Processor();
			$config = $processor->process($treeBuilder->buildTree(), array('item' => $config));

			$config['tags'] = $parent['tags'];
			$bulk->libraries()->add($definition->createAssetItem($name, $parent['parent'], $config));

			if (isset($config['assets'])) {
				$parent['parent'] .= ':'.$name;
				$this->assets($bulk, $parent, $path, $file, $config['assets']);
			}
		}


		private function parseAsset(Bulk $bulk, $parent, $name, array $config) {
			$treeBuilder = new TreeBuilder();
			$node = $treeBuilder->root('item');
			AssetsConfiguration::addAssetConfiguration($node);

			$definition = $this->assetTypeManager->getAssetType($config['type']);
			$definition->addExtraConfiguration($node);

			$processor = new Processor();
			$config = $processor->process($treeBuilder->buildTree(), array('item' => $config));

			$config['tags'] = $parent['tags'];
			$bulk->assets()->add($definition->createAssetItem($name, $parent['parent'], $config));
		}


		/**
		 * {@inheritdoc}
		 */
		public function supports($resource, $type = null) {
			return is_string($resource) &&
			in_array(pathinfo($resource, PATHINFO_EXTENSION), array('yml', 'yaml'), true) &&
			(!$type || in_array($type, array_keys(self::CONFIGURATIONS)));
		}
	}
