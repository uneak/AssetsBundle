<?php

	namespace Uneak\AssetsBundle\AssetItem\Asset;

	class InternalAsset extends Asset {

		protected $content;
		protected $template;
		protected $templateData;

		/**
		 * @return mixed
		 */
		public function getContent() {
			return $this->content;
		}

		/**
		 * @param mixed $content
		 *
		 * @return InternalAsset
		 */
		public function setContent($content) {
			$this->content = $content;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getTemplate() {
			return $this->template;
		}

		/**
		 * @param mixed $template
		 *
		 * @return InternalAsset
		 */
		public function setTemplate($template) {
			$this->template = $template;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getTemplateData() {
			return $this->templateData;
		}

		/**
		 * @param mixed $templateData
		 *
		 * @return InternalAsset
		 */
		public function setTemplateData($templateData) {
			$this->templateData = $templateData;

			return $this;
		}

		

		/**
		 * @param mixed $mixed
		 *
		 * @return array
		 */
		public function merge($mixed) {
			$mixed = parent::merge($mixed);

			if (isset($mixed['template'])) {
				$this->setTemplate($mixed['template']);
			}
			if (isset($mixed['template_data'])) {
				$this->setTemplateData($mixed['template_data']);
			}
			if (isset($mixed['content'])) {
				$this->setContent($mixed['content']);
			}

			return $mixed;
		}

		/**
		 * @return array
		 */
		public function toArray() {
			$data = parent::toArray();
			$data['template'] = $this->getTemplate();
			$data['template_data'] = $this->getTemplateData();
			$data['content'] = $this->getContent();
			return $data;
		}

		/**
		 * {@inheritdoc}
		 */
		public function unserialize($serialized) {
			$data = parent::unserialize($serialized);
			$this->setTemplate($data['template']);
			$this->setTemplateData($data['template_data']);
			$this->setContent($data['content']);
			return $data;
		}


	}