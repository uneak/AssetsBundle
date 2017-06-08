<?php

	namespace Uneak\AssetsBundle\Form\Type;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormInterface;
	use Symfony\Component\Form\FormView;
	use Uneak\AssetsBundle\AssetItem\AssetContainerInterface;
	use Uneak\AssetsBundle\Assets\Assets;
	use Uneak\AssetsBundle\Form\FormThemeInclude;
	use Uneak\AssetsBundle\Pool\AssetInclude;

	abstract class AssetAbstractType extends AbstractType implements AssetContainerInterface {

		/**
		 * {@inheritdoc}
		 */
		public function finishView(FormView $view, FormInterface $form, array $options) {

			$this->assetInclude($view->vars['asset_include'], $view->vars['assets'], $view, false);

			$formThemeInclude = new FormThemeInclude();
			$this->themeInclude($formThemeInclude);
			foreach ($formThemeInclude as $include) {
				if (!is_array($include)) {
					$include = array($include);
				}
				$view->vars['render_engine']->setTheme($view, $include);
			}

		}


		public function assetInclude(AssetInclude $include, Assets $assets, $parameters, $isVisited) {
		}

		public function themeInclude(FormThemeInclude $include) {
		}

	}