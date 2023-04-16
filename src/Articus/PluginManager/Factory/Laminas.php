<?php
declare(strict_types=1);

namespace Articus\PluginManager\Factory;

use Articus\PluginManager as PM;
use Laminas\ServiceManager\AbstractPluginManager;
use Psr\Container\ContainerInterface;

/**
 * @implements PM\ServiceFactoryInterface<PM\Laminas>
 */
class Laminas implements PM\ServiceFactoryInterface
{
	use PM\ConfigAwareFactoryTrait;

	public function __construct(string $configKey = PM\Laminas::class)
	{
		$this->configKey = $configKey;
	}

	public function __invoke(ContainerInterface $container, string $name): PM\Laminas
	{
		$config = $this->getServiceConfig($container);
		$laminasPluginManager = new class ($container, $config) extends AbstractPluginManager
		{
		};
		return new PM\Laminas($laminasPluginManager);
	}
}
