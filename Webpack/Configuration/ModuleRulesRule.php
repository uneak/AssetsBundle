<?php

	namespace Uneak\AssetsBundle\Webpack\Configuration;

	use Uneak\AssetsBundle\Javascript\JsItem\JsAbstract;

	class ModuleRulesRule extends JsAbstract implements ExtraInterface {

		use LoaderTrait, OptionTrait, QueryTrait, ExtraTrait;

		private $enforce;
		private $excludes = array();
		private $includes = array();
		private $issuer;
		private $oneOf;
		private $parser;
		private $resource;
		private $resourceQuery;
		private $rules;
		private $tests = array();
		/**
		 * @var UseEntry[]
		 */
		public $uses = array();


		// use
		//
		public function addUse(UseEntry $useEntry) {
			if (!$this->hasUse($useEntry)) {
				$this->uses[] = $useEntry;
			}

			return $this;
		}

		public function removeUse(UseEntry $useEntry) {
			if ($i = array_search($useEntry, $this->uses) !== false) {
				unset($this->uses[$i]);
			}

			return $this;
		}

		public function hasUse(UseEntry $useEntry) {
			return (array_search($useEntry, $this->uses) !== false);
		}

		/**
		 * @return UseEntry[]
		 */
		public function getUses() {
			return $this->uses;
		}

		/**
		 * @param UseEntry[] $uses
		 *
		 * @return $this
		 */
		public function setUses(array $uses) {
			foreach ($uses as $use) {
				$this->addUse($use);
			}

			return $this;
		}






		// test
		//
		public function addTest($regex) {
			if (!$this->hasTest($regex)) {
				$this->tests[] = $regex;
			}

			return $this;
		}

		public function removeTest($regex) {
			if ($i = array_search($regex, $this->tests) !== false) {
				unset($this->tests[$i]);
			}

			return $this;
		}

		public function hasTest($regex) {
			return (array_search($regex, $this->tests) !== false);
		}

		/**
		 * @return array
		 */
		public function getTests() {
			return $this->tests;
		}

		/**
		 * @param array $tests
		 *
		 * @return $this
		 */
		public function setTests(array $tests) {
			foreach ($tests as $test) {
				$this->addTest($test);
			}

			return $this;
		}





		// include
		//
		public function addInclude($path) {
			if (!$this->hasTest($path)) {
				$this->includes[] = $path;
			}

			return $this;
		}

		public function removeInclude($path) {
			if ($i = array_search($path, $this->includes) !== false) {
				unset($this->includes[$i]);
			}

			return $this;
		}

		public function hasInclude($path) {
			return (array_search($path, $this->includes) !== false);
		}

		/**
		 * @return array
		 */
		public function getIncludes() {
			return $this->includes;
		}

		/**
		 * @param array $includes
		 *
		 * @return $this
		 */
		public function setIncludes(array $includes) {
			foreach ($includes as $include) {
				$this->addTest($include);
			}

			return $this;
		}




		// exclude
		//
		public function addExclude($path) {
			if (!$this->hasTest($path)) {
				$this->excludes[] = $path;
			}

			return $this;
		}

		public function removeExclude($path) {
			if ($i = array_search($path, $this->excludes) !== false) {
				unset($this->excludes[$i]);
			}

			return $this;
		}

		public function hasExclude($path) {
			return (array_search($path, $this->excludes) !== false);
		}

		/**
		 * @return array
		 */
		public function getExcludes() {
			return $this->excludes;
		}

		/**
		 * @param array $excludes
		 *
		 * @return $this
		 */
		public function setExcludes(array $excludes) {
			foreach ($excludes as $exclude) {
				$this->addTest($exclude);
			}

			return $this;
		}


		protected function _getData() {
			return array(
				'test'    => (count($this->getTests()) == 1) ? $this->getTests()[0] : $this->getTests(),
				'include' => (count($this->getIncludes()) == 1) ? $this->getIncludes()[0] : $this->getIncludes(),
				'exclude' => (count($this->getExcludes()) == 1) ? $this->getExcludes()[0] : $this->getExcludes(),
				'options' => $this->getOptions(),
				'query'   => $this->getQueries(),
				'loader'  => $this->getLoaders(),
				'use'     => $this->getUses(),
			);
		}

	}
