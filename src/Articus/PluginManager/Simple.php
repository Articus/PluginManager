<?php
declare(strict_types=1);

namespace Articus\PluginManager;

use Psr\Container\ContainerInterface;

class Simple implements PluginManagerInterface
{
	/**
	 * @var ContainerInterface
	 */
	protected ContainerInterface $container;

	/**
	 * @var array<string, callable|PluginFactoryInterface>
	 */
	protected array $factories;

	/**
	 * @var array<string, string>
	 */
	protected array $aliases;

	/**
	 * @param ContainerInterface $container
	 * @param array<string, callable|PluginFactoryInterface> $factories
	 * @param array<string, string> $aliases
	 */
	public function __construct(ContainerInterface $container, array $factories, array $aliases)
	{
		$this->container = $container;
		$this->factories = $factories;
		$this->aliases = $aliases;
	}

	/**
	 * @inheritdoc
	 */
	public function __invoke(string $name, array $options)
	{
		$factory = $this->getFactory($name);
		return $factory($this->container, $name, $options);
	}

	/**
	 * @param string $name
	 * @return callable|PluginFactoryInterface
	 * @throws Exception\UnknownPlugin
	 */
	protected function getFactory(string $name): callable
	{
		$result = $this->factories[$this->aliases[$name] ?? $name] ?? null;
		if ($result === null)
		{
			throw new Exception\UnknownPlugin($name);
		}

		return $result;
	}
}
