<?php

	/*
	 * This file is part of the Symfony package.
	 *
	 * (c) Fabien Potencier <fabien@symfony.com>
	 *
	 * For the full copyright and license information, please view the LICENSE
	 * file that was distributed with this source code.
	 */

	namespace Uneak\AssetsBundle\Loader;

	use Symfony\Component\Config\Exception\FileLoaderLoadException;

	/**
	 * DelegatingLoader delegates loading to other loaders using a loader resolver.
	 *
	 * This loader acts as an array of LoaderInterface objects - each having
	 * a chance to load a given resource (handled by the resolver)
	 *
	 * @author Fabien Potencier <fabien@symfony.com>
	 */
	class DelegatingLoader extends Loader {

		private $loading = false;

		/**
		 * Constructor.
		 *
		 * @param LoaderResolverInterface $resolver A LoaderResolverInterface instance
		 */
		public function __construct(LoaderResolverInterface $resolver) {
			$this->resolver = $resolver;
		}

		/**
		 * {@inheritdoc}
		 */
		public function load($resource, $type = null, $parent = null) {

			if ($this->loading) {
				// This can happen if a fatal error occurs in parent::load().
				// Here is the scenario:
				// - while routes are being loaded by parent::load() below, a fatal error
				//   occurs (e.g. parse error in a controller while loading annotations);
				// - PHP abruptly empties the stack trace, bypassing all catch blocks;
				//   it then calls the registered shutdown functions;
				// - the ErrorHandler catches the fatal error and re-injects it for rendering
				//   thanks to HttpKernel->terminateWithException() (that calls handleException());
				// - at this stage, if we try to load the routes again, we must prevent
				//   the fatal error from occurring a second time,
				//   otherwise the PHP process would be killed immediately;
				// - while rendering the exception page, the router can be required
				//   (by e.g. the web profiler that needs to generate an URL);
				// - this handles the case and prevents the second fatal error
				//   by triggering an exception beforehand.

				throw new FileLoaderLoadException($resource);
			}
			$this->loading = true;

			if (false === $loader = $this->resolver->resolve($resource, $type)) {
				$this->loading = false;
				throw new FileLoaderLoadException($resource);
			}

			$bulk = $loader->load($resource, $type, $parent);
			$this->loading = false;

			return $bulk;
		}

		/**
		 * {@inheritdoc}
		 */
		public function supports($resource, $type = null) {
			return false !== $this->resolver->resolve($resource, $type);
		}
	}
