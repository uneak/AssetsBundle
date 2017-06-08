<?php

	namespace Uneak\AssetsBundle\AssetItem\Asset;

	use Uneak\AssetsBundle\AssetItem\TypedAssetItem;

	class Asset extends TypedAssetItem {
		/**
		 * @var string|null
		 */
		private $section;
		/**
		 * @var string
		 */
		private $file;


		/**
		 * @return string
		 */
		public function getFile() {
			return $this->file;
		}

		/**
		 * @param string $file
		 */
		public function setFile($file) {
			$this->file = $file;
		}

		/**
		 * @return string
		 */
		public function getSection() {
			return $this->section;
		}

		/**
		 * @param string $section
		 */
		public function setSection($section) {
			$this->section = $section;
		}


		/**
		 * @param mixed $mixed
		 *
		 * @return array
		 */
		public function merge($mixed) {
			$mixed = parent::merge($mixed);

			if (isset($mixed['section'])) {
				$this->setSection($mixed['section']);
			}
			if (isset($mixed['file'])) {
				$this->setFile($mixed['file']);
			}

			return $mixed;
		}

		/**
		 * @return array
		 */
		public function toArray() {
			$data = parent::toArray();
			$data['section'] = $this->getSection();
			$data['file'] = $this->getFile();
			return $data;
		}

		/**
		 * {@inheritdoc}
		 */
		public function unserialize($serialized) {
			$data = parent::unserialize($serialized);
			$this->setSection($data['section']);
			$this->setFile($data['file']);
			return $data;
		}


	}