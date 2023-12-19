<?php
declare(strict_types=1);

use Articus\PluginManager as PM;
use Psr\Container\ContainerInterface;

describe(PM\Factory\Chain::class, function ()
{
	afterEach(function ()
	{
		Mockery::close();
	});
	context('->__invoke', function ()
	{
		it('creates chain from callable', function ()
		{
			$configKey = 'test_config_key';
			$chainedManagerName = 'test_manager';
			$config = [
				'managers' => [
					$chainedManagerName
				],
			];
			$chainedManager = static fn () => null;

			$container = mock(ContainerInterface::class);
			$container->shouldReceive('get')->with('config')->andReturn([$configKey => $config])->once();
			$container->shouldReceive('get')->with($chainedManagerName)->andReturn($chainedManager)->once();

			$managerFactory = new PM\Factory\Chain($configKey);
			$manager = $managerFactory($container, 'test_service_name');
			expect($manager)->toBeAnInstanceOf(PM\Chain::class);
			expect(getProtectedProperty($manager, 'pluginManagers'))->toBe([$chainedManager]);
		});
		it('creates chain from plugin manager interface', function ()
		{
			$configKey = 'test_config_key';
			$chainedManagerName = 'test_manager';
			$config = [
				'managers' => [
					$chainedManagerName
				],
			];
			$chainedManager = mock(PM\PluginManagerInterface::class);

			$container = mock(ContainerInterface::class);
			$container->shouldReceive('get')->with('config')->andReturn([$configKey => $config])->once();
			$container->shouldReceive('get')->with($chainedManagerName)->andReturn($chainedManager)->once();

			$managerFactory = new PM\Factory\Chain($configKey);
			$manager = $managerFactory($container, 'test_service_name');
			expect($manager)->toBeAnInstanceOf(PM\Chain::class);
			expect(getProtectedProperty($manager, 'pluginManagers'))->toBe([$chainedManager]);
		});
		it('throws on non-callable chain link', function ()
		{
			$configKey = 'test_config_key';
			$chainedManagerName = 'test_manager';
			$config = [
				'managers' => [
					$chainedManagerName
				],
			];
			$chainedManager = mock();
			$exception = new InvalidArgumentException(sprintf('Invalid plugin manager "%s".', $chainedManagerName));

			$container = mock(ContainerInterface::class);
			$container->shouldReceive('get')->with('config')->andReturn([$configKey => $config])->once();
			$container->shouldReceive('get')->with($chainedManagerName)->andReturn($chainedManager)->once();

			$managerFactory = new PM\Factory\Chain($configKey);
			expect(fn () => $managerFactory($container, 'test_service_name'))->toThrow($exception);
		});
	});
});
