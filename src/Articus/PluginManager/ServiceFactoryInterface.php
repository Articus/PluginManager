<?php
declare(strict_types=1);

namespace Articus\PluginManager;

use Psr\Container\ContainerInterface;

/**
 * @template ServiceClass
 */
interface ServiceFactoryInterface
{
	/**
	 * @param ContainerInterface $container
	 * @param string $name
	 * @return ServiceClass
	 */
	public function __invoke(ContainerInterface $container, string $name);
}
