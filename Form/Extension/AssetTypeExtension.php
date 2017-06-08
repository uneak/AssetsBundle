<?php

	namespace Uneak\AssetsBundle\Form\Extension;

	use Symfony\Component\Form\AbstractTypeExtension;
	use Symfony\Component\Form\Extension\Core\Type\FormType;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\FormRendererEngineInterface;
	use Symfony\Component\Form\FormView;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Uneak\AssetsBundle\AssetItem\AssetContainerInterface;
	use Uneak\AssetsBundle\Assets\Assets;
	use Uneak\AssetsBundle\Pool\AssetInclude;

	class AssetTypeExtension extends AbstractTypeExtension {

		/**
		 * @var \Uneak\AssetsBundle\Pool\AssetInclude
		 */
		private $include;
		/**
		 * @var \Symfony\Component\Form\FormRendererEngineInterface
		 */
		private $rendererEngine;
		/**
		 * @var Assets
		 */
		private $assets;

		public function __construct(AssetInclude $include, Assets $assets, FormRendererEngineInterface $rendererEngine) {
			$this->include = $include;
			$this->rendererEngine = $rendererEngine;
			$this->assets = $assets;
		}


		public function buildView(FormView $view, FormInterface $form, array $options) {
			$type = $form->getConfig()->getType()->getInnerType();
			if ($type instanceof AssetContainerInterface) {
				$view->vars['asset_include'] = (isset($options['asset_include'])) ? $options['asset_include'] : $this->include;
				$view->vars['render_engine'] = $this->rendererEngine;
				$view->vars['assets'] = $this->assets;
			}
		}


		/**
		 * {@inheritdoc}
		 */
		public function configureOptions(OptionsResolver $resolver) {
			$resolver->setDefaults(array(
				'asset_include' => null,
			));
		}

		/**
		 * {@inheritdoc}
		 */
		public function getExtendedType() {
			return FormType::class;
		}
	}