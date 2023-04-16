<?php
declare(strict_types=1);

namespace Articus\PluginManager;

use Psr\Container\ContainerInterface;

/**
 * @template PluginClass
 */
interface PluginFactoryInterface
{
	/**
	 * @param ContainerInterface $container
	 * @param string $name
	 * @param array $options
	 * @return PluginClass
	 */
	public function __invoke(ContainerInterface $container, string $name, array $options = []);
}
