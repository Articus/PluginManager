<?php
declare(strict_types=1);

namespace Articus\PluginManager\Factory;

use Articus\PluginManager as PM;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use function is_callable;
use function sprintf;

/**
 * @implements PM\ServiceFactoryInterface<PM\Chain>
 */
class Chain implements PM\ServiceFactoryInterface
{
	use PM\ConfigAwareFactoryTrait;

	public function __construct(string $configKey = PM\Chain::class)
	{
		$this->configKey = $configKey;
	}

	public function __invoke(ContainerInterface $container, string $name): PM\Chain
	{
		$options = new PM\Options\Chain($this->getServiceConfig($container));
		$pluginManagers = [];
		foreach ($options->pluginManagers as $pluginManagerName)
		{
			$pluginManager = $container->get($pluginManagerName);
			if (!is_callable($pluginManager))
			{
				throw new InvalidArgumentException(sprintf('Invalid plugin manager "%s".', $pluginManagerName));
			}
			$pluginManagers[] = $pluginManager;
		}
		return new PM\Chain($pluginManagers);
	}
}
