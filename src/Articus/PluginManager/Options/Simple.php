<?php
declare(strict_types=1);

namespace Articus\PluginManager\Options;

use Articus\PluginManager\PluginFactoryInterface;

class Simple
{
	/**
	 * @var array<string, class-string>
	 */
	public array $invokables = [];

	/**
	 * @var array<string, class-string|callable|PluginFactoryInterface>
	 */
	public array $factories = [];

	/**
	 * @var array<string, string>
	 */
	public array $aliases = [];

	public function __construct(iterable $options)
	{
		foreach ($options as $key => $value)
		{
			switch ($key)
			{
				case 'invokables':
					$this->invokables = $value;
					break;
				case 'factories':
					$this->factories = $value;
					break;
				case 'aliases':
					$this->aliases = $value;
					break;
			}
		}
	}
}
