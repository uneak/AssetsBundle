<?php

	namespace Uneak\AssetsBundle\Finder;

	use Symfony\Component\HttpKernel\KernelInterface;

	class BundleFinderExtension extends FinderExtension {
		CONST VIEW_PATH = "/Resources/views";
		CONST PUBLIC_PATH = "/Resources/public";

		public function __construct(KernelInterface $kernel) {
			$links = array(
				"@Root" => array('path' => realpath($kernel->getRootDir() . "/../")),
				"@Public" => array('path' => realpath($kernel->getRootDir() . "/../web")),
				"@Resources" => array('path' => realpath($kernel->getRootDir() . "/Resources")),
				"@Logs" => array('path' => $kernel->getLogDir()),
				"@Cache" => array('path' => $kernel->getCacheDir()),
				"@UneakAssetsBundle:node" => array('path' => $kernel->getBundle("UneakAssetsBundle")->getPath() . "/Webpack/Node"),
			);

			foreach ($kernel->getBundles() as $bundle) {
				$links["@" . $bundle->getName() . ":view"] = array(
					'path' => $bundle->getPath() . self::VIEW_PATH
				);
				$links["@" . $bundle->getName() . ":public"] = array(
					'path' => $bundle->getPath() . self::PUBLIC_PATH
				);
				$links["@" . $bundle->getName()] = array(
					'path' => $bundle->getPath()
				);
			}
			parent::__construct($links);
		}
	}