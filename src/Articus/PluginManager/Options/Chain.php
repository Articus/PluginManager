<?php
declare(strict_types=1);

namespace Articus\PluginManager\Options;

class Chain
{
	/**
	 * @var string[]
	 */
	public array $pluginManagers = [];

	public function __construct(iterable $options)
	{
		foreach ($options as $key => $value)
		{
			switch ($key)
			{
				case 'managers':
				case 'plugin_managers':
				case 'pluginManagers':
					$this->pluginManagers = $value;
					break;
			}
		}
	}
}
