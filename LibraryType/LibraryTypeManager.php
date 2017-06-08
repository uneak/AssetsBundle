<?php

	namespace Uneak\AssetsBundle\LibraryType;

	use Uneak\AssetsBundle\Exception\LibraryTypeNotFoundException;

	class LibraryTypeManager {

		/**
		 * @var LibraryTypeInterface[]
		 */
		protected $libraryTypes = array();

		public function __construct(array $libraryTypes = array()) {
			$this->setLibraryTypes($libraryTypes);
		}

		/**
		 * @return LibraryTypeInterface[]
		 */
		public function getLibraryTypes() {
			return $this->libraryTypes;
		}

		/**
		 * @param $name
		 *
		 * @return LibraryTypeInterface
		 * @throws \Uneak\AssetsBundle\Exception\LibraryTypeNotFoundException
		 */
		public function getLibraryType($name) {
			if (!isset($this->libraryTypes[$name])) {
				throw new LibraryTypeNotFoundException(sprintf("LibraryType %s not found", $name));
			}

			return $this->libraryTypes[$name];
		}

		/**
		 * @param LibraryTypeInterface $libraryType
		 *
		 * @return $this
		 */
		public function addLibraryType(LibraryTypeInterface $libraryType) {
			$this->libraryTypes[$libraryType->getAlias()] = $libraryType;
			return $this;
		}

		/**
		 * @param array $libraryTypes
		 *
		 * @return $this
		 * @throws \Exception
		 */
		public function setLibraryTypes(array $libraryTypes) {
			foreach ($libraryTypes as $libraryType) {
				$this->addLibraryType($libraryType);
			}
			return $this;
		}

		/**
		 * @param string $name
		 *
		 * @return bool
		 */
		public function hasLibraryType($name) {
			return isset($this->libraryTypes[$name]);
		}

		/**
		 * @param string $name
		 *
		 * @return $this
		 */
		public function removeLibraryType($name) {
			unset($this->libraryTypes[$name]);
			return $this;
		}

	}