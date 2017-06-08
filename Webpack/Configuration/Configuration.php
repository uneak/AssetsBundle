<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	use Uneak\AssetsBundle\Javascript\Javascript;
	use Uneak\AssetsBundle\Javascript\JsItem\JsAbstract;

	class Configuration extends JsAbstract implements ConfigurationInterface {
		use ExtraTrait, WatchTrait, ContextTrait, ExtensionsTrait, ConfigurationPropertiesTrait;

		/**
		 * @var string
		 */
		private $name;
		/**
		 * @var bool
		 */
		private $export;
		private $target;


		public function __construct($name, $isExport = true, array $extensions = array()) {
			$this->name = $name;
			$this->export = $isExport;
			$this->setExtensions($extensions);
		}



	/**
		 * @return string
		 */
		public function getName() {
			return $this->name;
		}


		/**
		 * @return boolean
		 */
		public function isExport() {
			return $this->export;
		}

		/**
		 * @param boolean $isExport
		 */
		public function setExport($isExport) {
			$this->export = $isExport;
		}
		
		/**
		 * @return string
		 */
		public function getTarget() {
			return $this->target;
		}

		/**
		 * @param string $target
		 *
		 * @return string
		 */
		public function setTarget($target) {
			$this->target = $target;
			return $this;
		}

		

		protected function _getData() {
			return array(
				'name'      => $this->getName(),
				'target'      => $this->getTarget(),
				'context'      => $this->getContext(),
				'watch'        => $this->getWatch(),
				'entry'        => $this->entry,
				'output'       => $this->output,
				'module'       => $this->module,
				'devServer'    => $this->devServer,
				'externals'    => $this->externals,
				'node'         => $this->node,
				'performance'  => $this->performance,
				'plugins'      => $this->plugins,
				'resolve'      => $this->resolve,
				'stats'        => $this->stats,
				'watchOptions' => $this->watchOptions,
			);
		}




		public function jsRender() {
			$exp = sprintf("%s %s = ", "const", $this->getName());
			$exp .= parent::jsRender();
			$exp .= ';';

			$lines = array();
			$lines[] = Javascript::encode($this->header());
			$lines[] = $exp;
			$lines[] = Javascript::encode($this->footer());

			return implode(PHP_EOL, $lines);
		}

	}
