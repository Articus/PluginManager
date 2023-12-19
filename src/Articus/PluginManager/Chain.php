<?php
declare(strict_types=1);

namespace Articus\PluginManager;

class Chain implements PluginManagerInterface
{
	/**
	 * @var iterable<callable|PluginManagerInterface>
	 */
	protected iterable $pluginManagers;

	/**
	 * @param iterable<callable|PluginManagerInterface> $pluginManagers
	 */
	public function __construct(iterable $pluginManagers)
	{
		$this->pluginManagers = $pluginManagers;
	}

	/**
	 * @inheritdoc
	 */
	public function __invoke(string $name, array $options)
	{
		foreach ($this->pluginManagers as $pluginManager)
		{
			try
			{
				return $pluginManager($name, $options);
			}
			catch (Exception\UnknownPlugin $e)
			{
				//Ignore and check if next plugin manager in chain can create plugin
			}
		}
		throw new Exception\UnknownPlugin($name);
	}
}
