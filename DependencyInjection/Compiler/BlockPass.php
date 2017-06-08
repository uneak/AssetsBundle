<?php

	namespace Uneak\AssetsBundle\DependencyInjection\Compiler;

	use Symfony\Component\DependencyInjection\ContainerBuilder;
	use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

	/**
	 * Adds all services with the tags "block.type" and "block.type_extension" as
	 * arguments of the "uneak.assets.block.extension" service.
	 *
	 */
	class BlockPass implements CompilerPassInterface {
		public function process(ContainerBuilder $container) {

			if (!$container->hasDefinition('uneak.assets.block.extension')) {
				return;
			}
			$definition = $container->getDefinition('uneak.assets.block.extension');

			// Builds an array with fully-qualified type class names as keys and service IDs as values
			$types = array();

			foreach ($container->findTaggedServiceIds('block.type') as $serviceId => $tag) {
				$serviceDefinition = $container->getDefinition($serviceId);
				if (!$serviceDefinition->isPublic()) {
					throw new \InvalidArgumentException(sprintf('The service "%s" must be public as block types are lazy-loaded.', $serviceId));
				}

				// Support type access by FQCN
				$types[$serviceDefinition->getClass()] = $serviceId;
			}

			$definition->replaceArgument(1, $types);

			$typeExtensions = array();

			foreach ($container->findTaggedServiceIds('block.type_extension') as $serviceId => $tag) {
				$serviceDefinition = $container->getDefinition($serviceId);
				if (!$serviceDefinition->isPublic()) {
					throw new \InvalidArgumentException(sprintf('The service "%s" must be public as block type extensions are lazy-loaded.', $serviceId));
				}

				if (isset($tag[0]['extended_type'])) {
					$extendedType = $tag[0]['extended_type'];
				} else {
					throw new \InvalidArgumentException(sprintf('Tagged block type extension must have the extended type configured using the extended_type/extended-type attribute, none was configured for the "%s" service.', $serviceId));
				}

				$typeExtensions[$extendedType][] = $serviceId;
			}

			$definition->replaceArgument(2, $typeExtensions);

		}
	}
