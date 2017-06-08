<?php

	namespace Uneak\AssetsBundle\AssetItem;

	use Uneak\AssetsBundle\Exception\UnmergeableException;
	use Symfony\Component\Config\Resource\ResourceInterface;

	abstract class AssetItem implements AssetItemInterface {
		/**
		 * @var string
		 */
		protected $inputDir;
		/**
		 * @var string
		 */
		protected $outputDir;
		/**
		 * @var string
		 */
		protected $path;
		/**
		 * @var string
		 */
		protected $name;
		/**
		 * @var array
		 */
		protected $parameters = array();
		/**
		 * @var \Symfony\Component\Config\Resource\ResourceInterface[]
		 */
		protected $resources = array();
		/**
		 * @var string
		 */
		protected $parent = '_bulk';
		/**
		 * @var string[]
		 */
		protected $tags = array();

		public function __construct($name, $parent = null, array $options = array()) {
			$this->name = $name;
			$this->parent = $parent;
			$this->merge($options);
		}

		/**
		 * @return string
		 */
		public function getName() {
			return $this->name;
		}

		/**
		 * @return string
		 */
		public function getId() {
			return $this->parent . ':' . $this->name;
		}


		/**
		 * @return array
		 */
		public function getParameters() {
			return $this->parameters;
		}

		/**
		 * @param array $parameters
		 */
		public function setParameters($parameters) {
			$this->parameters = $parameters;
		}


		/**
		 * @return string
		 */
		public function getParent() {
			return $this->parent;
		}

		/**
		 * @param string $parent
		 */
		public function setParent($parent) {
			$this->parent = $parent;
		}


		/**
		 * @param string $tag
		 *
		 * @return $this
		 */
		public function addTag($tag) {
			$this->tags[] = $tag;

			return $this;
		}

		/**
		 * @return string[]
		 */
		public function getTags() {
			return $this->tags;
		}

		/**
		 * @param string[] $tags
		 *
		 * @return $this
		 */
		public function setTags(array $tags) {
			foreach ($tags as $tag) {
				$this->addTag($tag);
			}

			return $this;
		}

		/**
		 * @return string
		 */
		public function getInputDir() {
			return $this->inputDir;
		}

		/**
		 * @param string $inputDir
		 */
		public function setInputDir($inputDir) {
			$this->inputDir = $inputDir;
		}

		/**
		 * @return string
		 */
		public function getOutputDir() {
			return $this->outputDir;
		}

		/**
		 * @param string $dir
		 */
		public function setOutputDir($dir) {
			$this->outputDir = $dir;
		}


		/**
		 * @return string
		 */
		public function getPath() {
			return $this->path;
		}

		/**
		 * @param string $path
		 */
		public function setPath($path) {
			$this->path = $path;
		}

		/**
		 * @return \Symfony\Component\Config\Resource\ResourceInterface[]
		 */
		public function getResources() {
			return array_unique($this->resources);
		}

		/**
		 * @param \Symfony\Component\Config\Resource\ResourceInterface $resource
		 *
		 * @return $this
		 */
		public function addResource(ResourceInterface $resource) {
			$this->resources[] = $resource;

			return $this;
		}

		/**
		 * @param mixed $mixed
		 *
		 * @return array
		 */
		public function merge($mixed) {
			if ($mixed instanceof AssetItemInterface) {
				$this->resources = array_merge($this->resources, $mixed->getResources());
				$mixed = $mixed->toArray();
			}
			if (!is_array($mixed)) {
				throw new UnmergeableException();
			}

			if (isset($mixed['parameters'])) {
				$this->setParameters($mixed['parameters']);
			}
			if (isset($mixed['path'])) {
				$this->setPath($mixed['path']);
			}
			if (isset($mixed['output_dir'])) {
				$this->setOutputDir($mixed['output_dir']);
			}
			if (isset($mixed['input_dir'])) {
				$this->setInputDir($mixed['input_dir']);
			}
			if (isset($mixed['tags'])) {
				$this->setTags($mixed['tags']);
			}

			return $mixed;
		}

		/**
		 * @return array
		 */
		public function toArray() {
			return array(
				'name'       => $this->getName(),
				'parent'     => $this->getParent(),
				'parameters' => $this->getParameters(),
				'input_dir'  => $this->getInputDir(),
				'output_dir' => $this->getOutputDir(),
				'path'       => $this->getPath(),
				'tags'       => $this->getTags(),
				'resources'  => $this->getResources()
			);
		}

		/**
		 * {@inheritdoc}
		 */
		public function serialize() {
			return serialize($this->toArray());
		}

		/**
		 * {@inheritdoc}
		 */
		public function unserialize($serialized) {
			$data = unserialize($serialized);
			$this->name = $data['name'];
			$this->setParent($data['parent']);
			$this->setParameters($data['parameters']);
			$this->setInputDir($data['input_dir']);
			$this->setOutputDir($data['output_dir']);
			$this->setPath($data['path']);
			$this->setTags($data['tags']);
			$this->resources = $data['resources'];

			return $data;
		}


	}