<?php
declare(strict_types=1);

namespace Articus\PluginManager;

use Psr\Container\ContainerInterface;
use function serialize;

/**
 * @template PluginClass
 * @implements PluginManagerInterface<PluginCLass>
 */
class Simple implements PluginManagerInterface
{
	/**
	 * @var ContainerInterface
	 */
	protected ContainerInterface $container;

	/**
	 * @var array<string, callable|PluginFactoryInterface<PluginClass>>
	 */
	protected array $factories;

	/**
	 * @var array<string, string>
	 */
	protected array $aliases;

	/**
	 * @var array<string, bool>
	 */
	protected array $shares;

	/**
	 * @var array<string, array<string, PluginClass>>
	 */
	protected array $plugins = [];

	/**
	 * @param ContainerInterface $container
	 * @param array<string, callable|PluginFactoryInterface> $factories
	 * @param array<string, string> $aliases
	 * @param array<string, bool> $shares
	 */
	public function __construct(ContainerInterface $container, array $factories, array $aliases, array $shares)
	{
		$this->container = $container;
		$this->factories = $factories;
		$this->aliases = $aliases;
		$this->shares = $shares;
	}

	/**
	 * @inheritdoc
	 */
	public function __invoke(string $name, array $options)
	{
		$result = null;
		$pluginName = $this->aliases[$name] ?? $name;
		if ($this->shares[$pluginName] ?? false)
		{
			$pluginHash = serialize($options);
			$result = $this->plugins[$pluginName][$pluginHash] ?? null;
			if ($result === null)
			{
				$result = $this->createPlugin($pluginName, $options);
				$this->plugins[$pluginName][$pluginHash] = $result;
			}
		}
		else
		{
			$result = $this->createPlugin($pluginName, $options);
		}
		return $result;
	}

	/**
	 * @param string $name
	 * @param array $options
	 * @return PluginClass
	 * @throws Exception\UnknownPlugin
	 */
	protected function createPlugin(string $name, array $options)
	{
		$factory = $this->factories[$name] ?? null;
		if ($factory === null)
		{
			throw new Exception\UnknownPlugin($name);
		}

		return $factory($this->container, $name, $options);
	}
}
