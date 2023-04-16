<?php
declare(strict_types=1);

namespace Articus\PluginManager;

use Psr\Container\ContainerInterface;

/**
 * Trait for factories that require configuration from "config" service
 */
trait ConfigAwareFactoryTrait
{
	/**
	 * Key inside "config" service
	 * @var string
	 */
	protected string $configKey;

	/**
	 * Small hack to simplify configuration when you want to pass custom config key but do not want to create extra class or anonymous function.
	 * So for example in your configuration YAML file you can use:
	 * dependencies:
	 *   factories:
	 *     my_service: [My\Service\ConfigAwareFactory, my_service_config]
	 * my_service_config:
	 *   parameter: value
	 * @param string $name
	 * @param array $arguments
	 * @return static
	 */
	public static function __callStatic(string $name, array $arguments)
	{
		return (new static($name))(...$arguments);
	}

	/**
	 * Extracts service configuration from container
	 * @param ContainerInterface $container
	 * @return array
	 */
	protected function getServiceConfig(ContainerInterface $container): array
	{
		return $container->get('config')[$this->configKey] ?? [];
	}
}
