<?php
declare(strict_types=1);

namespace spec\Example;

use Articus\PluginManager as PM;
use Psr\Container\ContainerInterface;

class InvokableFactory implements PM\PluginFactoryInterface
{
	public function __invoke(ContainerInterface $container, string $name, array $options = [])
	{
		return new InvokableService($options);
	}
}
