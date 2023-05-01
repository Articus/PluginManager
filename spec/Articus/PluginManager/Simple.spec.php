<?php
declare(strict_types=1);

use Articus\PluginManager as PM;
use Psr\Container\ContainerInterface;

describe(PM\Simple::class, function ()
{
	afterEach(function ()
	{
		Mockery::close();
	});
	context('->__invoke', function ()
	{
		it('creates plugin by name', function ()
		{
			$container = mock(ContainerInterface::class);
			$name = 'test_plugin_name';
			$options = ['test' => 123];
			$factory = mock(PM\PluginFactoryInterface::class);
			$plugin = mock();
			$factory->shouldReceive('__invoke')->with($container, $name, $options)->andReturn($plugin)->once();

			$manager = new PM\Simple($container, [$name => $factory], [], []);
			expect($manager($name, $options))->toBe($plugin);
		});
		it('creates plugin by alias', function ()
		{
			$container = mock(ContainerInterface::class);
			$name = 'test_plugin_name';
			$alias = 'test_plugin_alias';
			$options = ['test' => 123];
			$factory = mock(PM\PluginFactoryInterface::class);
			$plugin = mock();
			$factory->shouldReceive('__invoke')->with($container, $name, $options)->andReturn($plugin)->once();

			$manager = new PM\Simple($container, [$name => $factory], [$alias => $name], []);
			expect($manager($alias, $options))->toBe($plugin);
		});
		it('throws on unknown name', function ()
		{
			$container = mock(ContainerInterface::class);
			$name = 'test_plugin_name';
			$options = ['test' => 123];
			$exception = new PM\Exception\UnknownPlugin($name);

			$manager = new PM\Simple($container, [], [], []);
			expect(fn () => $manager($name, $options))->toThrow($exception);
		});
		it('creates different plugin instances for unshared plugin name and different options', function ()
		{
			$container = mock(ContainerInterface::class);
			$name = 'test_plugin_name';
			$options1 = ['test' => 123];
			$options2 = ['test' => 456];
			$factory = mock(PM\PluginFactoryInterface::class);
			$plugin1 = mock();
			$plugin2 = mock();
			$factory->shouldReceive('__invoke')->with($container, $name, $options1)->andReturn($plugin1)->once();
			$factory->shouldReceive('__invoke')->with($container, $name, $options2)->andReturn($plugin2)->once();

			$manager = new PM\Simple($container, [$name => $factory], [], []);
			expect($manager($name, $options1))->toBe($plugin1);
			expect($manager($name, $options2))->toBe($plugin2);
		});
		it('creates different plugin instances for unshared plugin name and equal options', function ()
		{
			$container = mock(ContainerInterface::class);
			$name = 'test_plugin_name';
			$options = ['test' => 123];
			$factory = mock(PM\PluginFactoryInterface::class);
			$plugin1 = mock();
			$plugin2 = mock();
			$factory->shouldReceive('__invoke')->with($container, $name, $options)->andReturn($plugin1, $plugin2)->twice();

			$manager = new PM\Simple($container, [$name => $factory], [], []);
			expect($manager($name, $options))->toBe($plugin1);
			expect($manager($name, $options))->toBe($plugin2);
		});
		it('creates different plugin instances for shared plugin name and different options', function ()
		{
			$container = mock(ContainerInterface::class);
			$name = 'test_plugin_name';
			$options1 = ['test' => 123];
			$options2 = ['test' => 456];
			$factory = mock(PM\PluginFactoryInterface::class);
			$plugin1 = mock();
			$plugin2 = mock();
			$factory->shouldReceive('__invoke')->with($container, $name, $options1)->andReturn($plugin1)->once();
			$factory->shouldReceive('__invoke')->with($container, $name, $options2)->andReturn($plugin2)->once();

			$manager = new PM\Simple($container, [$name => $factory], [], [$name => true]);
			expect($manager($name, $options1))->toBe($plugin1);
			expect($manager($name, $options2))->toBe($plugin2);
		});
		it('creates single plugin instance for shared plugin name and equal options', function ()
		{
			$container = mock(ContainerInterface::class);
			$name = 'test_plugin_name';
			$options = ['test' => 123];
			$factory = mock(PM\PluginFactoryInterface::class);
			$plugin = mock();
			$factory->shouldReceive('__invoke')->with($container, $name, $options)->andReturn($plugin)->once();

			$manager = new PM\Simple($container, [$name => $factory], [], [$name => true]);
			expect($manager($name, $options))->toBe($plugin);
			expect($manager($name, $options))->toBe($plugin);
		});
	});
});
