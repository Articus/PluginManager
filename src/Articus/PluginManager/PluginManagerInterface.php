<?php
declare(strict_types=1);

namespace Articus\PluginManager;

/**
 * @template PluginClass
 */
interface PluginManagerInterface
{
	/**
	 * @param string $name
	 * @param array $options
	 * @return PluginClass
	 */
	public function __invoke(string $name, array $options);
}
