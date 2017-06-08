<?php

	namespace Uneak\AssetsBundle\Javascript\JsItem;
	
	use Uneak\AssetsBundle\Javascript\Javascript;

	class JsList extends JsAbstract {
		/**
		 * @var array
		 */
		protected $lines = array();

		/**
		 * @return array
		 */
		public function getLines() {
			return $this->lines;
		}

		/**
		 * @param array $lines
		 *
		 * @return $this
		 */
		public function setLines(array $lines) {
			foreach ($lines as $line) {
				$this->addLine($line);
			}
			return $this;
		}

		/**
		 * @param mixed $line
		 *
		 * @return $this
		 */
		public function addLine($line) {
			if (false === array_search($line, $this->lines)) {
				$this->lines[] = $line;
			}

			return $this;
		}

		public function removeLine($line) {
			if (false !== $i = array_search($line, $this->lines)) {
				unset($this->lines[$i]);
			}

			return $this;
		}

		public function hasLine($line) {
			return (false !== array_search($line, $this->lines));
		}



		protected function _getData() {
			return $this->getLines();
		}
		
		
		public function jsRender() {
			return implode(PHP_EOL, array_map(function($line) {
				return Javascript::encode($line);
			}, $this->_dump()));
		}

	}
