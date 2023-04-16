<?php
declare(strict_types=1);

use spec\Example;
use Articus\PluginManager as PM;
use Psr\Container\ContainerInterface;

describe(PM\Factory\Simple::class, function ()
{
	afterEach(function ()
	{
		Mockery::close();
	});
	context('->__invoke', function ()
	{
		it('supports plugin class name in configuration', function ()
		{
			$pluginName = 'test_plugin_name';
			$pluginOptions = ['test' => 123];
			$configKey = 'test_config_key';
			$config = [
				'invokables' => [$pluginName => Example\InvokableService::class],
			];
			$container = mock(ContainerInterface::class);
			$container->shouldReceive('get')->with('config')->andReturn([$configKey => $config])->once();

			$managerFactory = new PM\Factory\Simple($configKey);
			$manager = $managerFactory($container, 'test_service_name');
			$plugin = $manager($pluginName, $pluginOptions);
			expect($plugin)->toBeAnInstanceOf(Example\InvokableService::class);
			if ($plugin instanceof Example\InvokableService)
			{
				expect($plugin->getOptions())->toBe($pluginOptions);
			}
		});
		it('supports plugin factory name in configuration', function ()
		{
			$pluginName = 'test_plugin_name';
			$pluginOptions = ['test' => 123];
			$configKey = 'test_config_key';
			$config = [
				'factories' => [$pluginName => Example\InvokableFactory::class],
			];
			$container = mock(ContainerInterface::class);
			$container->shouldReceive('get')->with('config')->andReturn([$configKey => $config])->once();

			$managerFactory = new PM\Factory\Simple($configKey);
			$manager = $managerFactory($container, 'test_service_name');
			$plugin = $manager($pluginName, $pluginOptions);
			expect($plugin)->toBeAnInstanceOf(Example\InvokableService::class);
			if ($plugin instanceof Example\InvokableService)
			{
				expect($plugin->getOptions())->toBe($pluginOptions);
			}
		});
		it('supports plugin factory instance in configuration', function ()
		{
			$pluginName = 'test_plugin_name';
			$pluginOptions = ['test' => 123];
			$pluginFactory = mock(PM\PluginFactoryInterface::class);
			$plugin = mock();
			$configKey = 'test_config_key';
			$config = [
				'factories' => [$pluginName => $pluginFactory],
			];
			$container = mock(ContainerInterface::class);

			$container->shouldReceive('get')->with('config')->andReturn([$configKey => $config])->once();
			$pluginFactory->shouldReceive('__invoke')->with($container, $pluginName, $pluginOptions)->andReturn($plugin)->once();

			$managerFactory = new PM\Factory\Simple($configKey);
			$manager = $managerFactory($container, 'test_service_name');
			expect($manager($pluginName, $pluginOptions))->toBe($plugin);
		});
		it('supports plugin class name alias in configuration', function ()
		{
			$pluginName = 'test_plugin_name';
			$pluginAlias = 'test_plugin_alias';
			$pluginOptions = ['test' => 123];
			$configKey = 'test_config_key';
			$config = [
				'invokables' => [$pluginName => Example\InvokableService::class],
				'aliases' => [$pluginAlias => $pluginName],
			];
			$container = mock(ContainerInterface::class);
			$container->shouldReceive('get')->with('config')->andReturn([$configKey => $config])->once();

			$managerFactory = new PM\Factory\Simple($configKey);
			$manager = $managerFactory($container, 'test_service_name');
			$plugin = $manager($pluginAlias, $pluginOptions);
			expect($plugin)->toBeAnInstanceOf(Example\InvokableService::class);
			if ($plugin instanceof Example\InvokableService)
			{
				expect($plugin->getOptions())->toBe($pluginOptions);
			}
		});
		it('supports plugin factory alias in configuration', function ()
		{
			$pluginName = 'test_plugin_name';
			$pluginAlias = 'test_plugin_alias';
			$pluginOptions = ['test' => 123];
			$pluginFactory = mock(PM\PluginFactoryInterface::class);
			$plugin = mock();
			$configKey = 'test_config_key';
			$config = [
				'factories' => [$pluginName => $pluginFactory],
				'aliases' => [$pluginAlias => $pluginName],
			];
			$container = mock(ContainerInterface::class);

			$container->shouldReceive('get')->with('config')->andReturn([$configKey => $config])->once();
			$pluginFactory->shouldReceive('__invoke')->with($container, $pluginAlias, $pluginOptions)->andReturn($plugin)->once();

			$managerFactory = new PM\Factory\Simple($configKey);
			$manager = $managerFactory($container, 'test_service_name');
			expect($manager($pluginAlias, $pluginOptions))->toBe($plugin);
		});
		it('throws on non-callable plugin factory instance', function ()
		{
			$pluginName = 'test_plugin_name';
			$configKey = 'test_config_key';
			$config = [
				'factories' => [$pluginName => new stdClass()],
			];
			$container = mock(ContainerInterface::class);
			$container->shouldReceive('get')->with('config')->andReturn([$configKey => $config])->once();
			$exception = new InvalidArgumentException(sprintf('Invalid factory for plugin "%s".', $pluginName));

			$managerFactory = new PM\Factory\Simple($configKey);
			expect(fn () => $managerFactory($container, 'test_service_name'))->toThrow($exception);
		});
	});
});
