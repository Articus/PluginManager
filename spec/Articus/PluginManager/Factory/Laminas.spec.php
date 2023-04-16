<?php
declare(strict_types=1);

use Articus\PluginManager as PM;
use Laminas\ServiceManager\AbstractPluginManager;
use Psr\Container\ContainerInterface;

describe(PM\Factory\Laminas::class, function ()
{
	afterEach(function ()
	{
		Mockery::close();
	});
	context('->__invoke', function ()
	{
		it('creates manager', function ()
		{
			skipIf(!class_exists(AbstractPluginManager::class));

			$configKey = 'test_config_key';
			$config = [
				'invokables' => [],
				'factories' => [],
			];
			$container = mock(ContainerInterface::class);
			$container->shouldReceive('get')->with('config')->andReturn([$configKey => $config])->once();

			$managerFactory = new PM\Factory\Laminas($configKey);
			$manager = $managerFactory($container, 'test_service_name');
			expect($manager)->toBeAnInstanceOf(PM\Laminas::class);
			//TODO how to introspect created manager?
		});
	});
});
