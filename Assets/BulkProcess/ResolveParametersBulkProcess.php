<?php

	namespace Uneak\AssetsBundle\Assets\BulkProcess;


	use Uneak\AssetsBundle\AssetItem\Asset\Asset;
	use Uneak\AssetsBundle\AssetItem\Bulk\BulkInterface;
	use Uneak\AssetsBundle\AssetItem\Library\Library;
	use Uneak\AssetsBundle\AssetItem\Package\Package;
	use Uneak\AssetsBundle\Assets\AbstractBulkProcess;
	use Symfony\Component\DependencyInjection\ContainerInterface;
	use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
	use Symfony\Component\DependencyInjection\Exception\RuntimeException;
	

	class ResolveParametersBulkProcess extends AbstractBulkProcess {

		/**
		 * @var \Symfony\Component\DependencyInjection\ContainerInterface
		 */
		private $container;

		public function __construct(ContainerInterface $container) {
			$this->container = $container;
		}
		
		public function process(BulkInterface $bulk) {
			/** @var $package Package */
			foreach ($bulk->packages()->all() as $package) {
				//				$package->updateBowerConfiguration('allow_root', $this->resolve($package->getBowerConfiguration('allow_root')));
				//				$package->updateBowerConfiguration('bin', $this->resolve($package->getBowerConfiguration('bin')));
				//				$package->updateBowerConfiguration('registry', $this->resolve($package->getBowerConfiguration('registry')));
				$package->setPath($this->resolve($package->getPath()));
				$package->setInputDir($this->resolve($package->getInputDir()));
				$package->setOutputDir($this->resolve($package->getOutputDir()));
			}

			/** @var $library Library */
			foreach ($bulk->libraries()->all() as $library) {
				$library->setParameters($this->resolve($library->getParameters()));
				$library->setMain($this->resolve($library->getMain()));
				$package->setPath($this->resolve($package->getPath()));
				$package->setInputDir($this->resolve($package->getInputDir()));
				$package->setOutputDir($this->resolve($package->getOutputDir()));
			}

			/** @var $asset Asset */
			foreach ($bulk->assets()->all() as $asset) {
				$asset->setDependencies($this->resolve($asset->getDependencies()));
				$asset->setParameters($this->resolve($asset->getParameters()));
				$package->setPath($this->resolve($package->getPath()));
				$package->setInputDir($this->resolve($package->getInputDir()));
				$package->setOutputDir($this->resolve($package->getOutputDir()));

				if ($asset->getFile()) {
					$asset->setFile($this->resolve($asset->getFile()));
				}
				if ($asset->getSection()) {
					$asset->setSection($this->resolve($asset->getSection()));
				}

			}

		}


		/**
		 * Recursively replaces placeholders with the service container parameters.
		 *
		 * @param mixed $value The source which might contain "%placeholders%"
		 *
		 * @return mixed The source with the placeholders replaced by the container
		 *               parameters. Arrays are resolved recursively.
		 *
		 * @throws ParameterNotFoundException When a placeholder does not exist as a container parameter
		 * @throws RuntimeException           When a container value is not a string or a numeric value
		 */
		private function resolve($value) {
			if (is_array($value)) {
				foreach ($value as $key => $val) {
					$value[$key] = $this->resolve($val);
				}

				return $value;
			}

			if (!is_string($value)) {
				return $value;
			}

			$container = $this->container;

			$escapedValue = preg_replace_callback('/%%|%([^%\s]++)%/', function ($match) use ($container, $value) {
				// skip %%
				if (!isset($match[1])) {
					return '%%';
				}

				$resolved = $container->getParameter($match[1]);

				if (is_string($resolved) || is_numeric($resolved)) {
					return (string)$resolved;
				}

				throw new RuntimeException(sprintf(
						'The container parameter "%s", used in the route configuration value "%s", ' .
						'must be a string or numeric, but it is of type %s.',
						$match[1],
						$value,
						gettype($resolved)
					)
				);

			}, $value);

			return str_replace('%%', '%', $escapedValue);
		}

		
	}