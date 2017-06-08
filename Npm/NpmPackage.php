<?php

	namespace Uneak\AssetsBundle\Npm;


	class NpmPackage implements NpmPackageInterface {
		/**
		 * @var string
		 */
		private $path;
		/**
		 * @var string
		 */
		private $modulesPath;
		/**
		 * @var array
		 */
		private $nodeModules = array();


		public function __construct($path, $modulesPathPrefix = 'node_modules') {
			$this->path = $path;
			$this->modulesPath = $path . DIRECTORY_SEPARATOR . $modulesPathPrefix;
			$data = json_decode(file_get_contents($this->path . "/package.json"), true);

			$dependencies = array();
			$dependencies = (isset($data['dependencies'])) ? array_merge($dependencies, $data['dependencies']) : $dependencies;
			$dependencies = (isset($data['devDependencies'])) ? array_merge($dependencies, $data['devDependencies']) : $dependencies;
			
			foreach ($dependencies as $name => $version) {
				$this->nodeModules[$name] = $version;
			}
		}

		/**
		 * @return string
		 */
		public function getPath() {
			return $this->path;
		}

		/**
		 * @return string
		 */
		public function getModulesPath() {
			return $this->modulesPath;
		}
		
		/**
		 * @return array
		 */
		public function getNodeModules() {
			return $this->nodeModules;
		}
		
		/**
		 * @param $name
		 *
		 * @return bool
		 */
		public function hasNodeModule($name) {
			return isset($this->nodeModules[$name]);
		}
		

		/**
		 * {@inheritdoc}
		 */
		public function serialize() {
			return serialize(array(
				$this->path,
				$this->modulesPath,
				$this->nodeModules,
			));
		}

		/**
		 * {@inheritdoc}
		 */
		public function unserialize($serialized) {
			list(
				$this->path,
				$this->modulesPath,
				$this->nodeModules,
				) = unserialize($serialized);
		}
	}
