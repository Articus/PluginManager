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

			$manager = new PM\Simple($container, [$name => $factory], []);
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
			$factory->shouldReceive('__invoke')->with($container, $alias, $options)->andReturn($plugin)->once();

			$manager = new PM\Simple($container, [$name => $factory], [$alias => $name]);
			expect($manager($alias, $options))->toBe($plugin);
		});
		it('throws on unknown name', function ()
		{
			$container = mock(ContainerInterface::class);
			$name = 'test_plugin_name';
			$options = ['test' => 123];
			$exception = new PM\Exception\UnknownPlugin($name);

			$manager = new PM\Simple($container, [], []);
			expect(fn () => $manager($name, $options))->toThrow($exception);
		});
	});
});
