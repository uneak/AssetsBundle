<?php

	namespace Uneak\AssetsBundle\Block\Extension\DependencyInjection;

	use Uneak\AssetsBundle\Block\Exception\InvalidArgumentException;
	use Symfony\Component\DependencyInjection\ContainerInterface;
	use Uneak\AssetsBundle\Block\BlockExtensionInterface;

	class DependencyInjectionExtension implements BlockExtensionInterface {
		private $container;
		private $typeServiceIds;
		private $typeExtensionServiceIds;

		public function __construct(ContainerInterface $container, array $typeServiceIds, array $typeExtensionServiceIds) {
			$this->container = $container;
			$this->typeServiceIds = $typeServiceIds;
			$this->typeExtensionServiceIds = $typeExtensionServiceIds;
		}

		public function getType($name) {
			if (!isset($this->typeServiceIds[$name])) {
				throw new InvalidArgumentException(sprintf('The field type "%s" is not registered with the service container.', $name));
			}

			return $this->container->get($this->typeServiceIds[$name]);
		}

		public function hasType($name) {
			return isset($this->typeServiceIds[$name]);
		}

		public function getTypeExtensions($name) {
			$extensions = array();

			if (isset($this->typeExtensionServiceIds[$name])) {
				foreach ($this->typeExtensionServiceIds[$name] as $serviceId) {
					$extensions[] = $extension = $this->container->get($serviceId);

					// validate result of getExtendedType() to ensure it is consistent with the service definition
					if ($extension->getExtendedType() !== $name) {
						throw new InvalidArgumentException(
							sprintf('The extended type specified for the service "%s" does not match the actual extended type. Expected "%s", given "%s".',
								$serviceId,
								$name,
								$extension->getExtendedType()
							)
						);
					}
				}
			}

			return $extensions;
		}

		public function hasTypeExtensions($name) {
			return isset($this->typeExtensionServiceIds[$name]);
		}

	}
