<?php
declare(strict_types=1);

namespace Articus\PluginManager\Factory;

use Articus\PluginManager\PluginFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 * @template PluginClass
 * @implements PluginFactoryInterface<PluginClass>
 */
class InvokablePlugin implements PluginFactoryInterface
{
	/**
	 * @var class-string<PluginClass>
	 */
	protected string $pluginClassName;

	/**
	 * @param class-string<PluginClass> $pluginClassName
	 */
	public function __construct(string $pluginClassName)
	{
		$this->pluginClassName = $pluginClassName;
	}

	/**
	 * @param ContainerInterface $container
	 * @param string $name
	 * @param array $options
	 * @return PluginClass
	 */
	public function __invoke(ContainerInterface $container, string $name, array $options = [])
	{
		return new $this->pluginClassName($options);
	}
}
