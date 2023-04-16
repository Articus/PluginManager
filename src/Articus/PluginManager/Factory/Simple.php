<?php
declare(strict_types=1);

namespace Articus\PluginManager\Factory;

use Articus\PluginManager as PM;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use function class_exists;
use function is_callable;
use function is_string;
use function sprintf;

/**
 * @implements PM\ServiceFactoryInterface<PM\Simple>
 */
class Simple implements PM\ServiceFactoryInterface
{
	use PM\ConfigAwareFactoryTrait;

	public function __construct(string $configKey = PM\Simple::class)
	{
		$this->configKey = $configKey;
	}

	public function __invoke(ContainerInterface $container, string $name): PM\Simple
	{
		$options = new PM\Options\Simple($this->getServiceConfig($container));
		$factories = [];
		foreach ($options->invokables as $pluginName => $pluginClassName)
		{
			$factories[$pluginName] = new InvokablePlugin($pluginClassName);
		}
		foreach ($options->factories as $pluginName => $pluginFactory)
		{
			if (is_string($pluginFactory) && class_exists($pluginFactory))
			{
				$pluginFactory = new $pluginFactory();
			}

			if (!is_callable($pluginFactory))
			{
				throw new InvalidArgumentException(sprintf('Invalid factory for plugin "%s".', $pluginName));
			}

			$factories[$pluginName] = $pluginFactory;
		}
		return new PM\Simple($container, $factories, $options->aliases);
	}
}
