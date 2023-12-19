<?php
declare(strict_types=1);

use Articus\PluginManager as PM;

describe(PM\Chain::class, function ()
{
	afterEach(function ()
	{
		Mockery::close();
	});
	context('->__invoke', function ()
	{
		it('returns plugin created by the first plugin manager in chain if it can create this plugin', function ()
		{
			$name = 'test_plugin_name';
			$options = ['test' => 123];
			$plugin = mock();

			$chainedManager1 = mock(PM\PluginManagerInterface::class);
			$chainedManager1->shouldReceive('__invoke')->with($name, $options)->andReturn($plugin)->once();

			$chainedManager2 = mock(PM\PluginManagerInterface::class);

			$manager = new PM\Chain([$chainedManager1, $chainedManager2]);
			expect($manager($name, $options))->toBe($plugin);
		});
		it('returns plugin created by the second plugin manager in chain if it can create this plugin but the first plugin manager can not', function ()
		{
			$name = 'test_plugin_name';
			$options = ['test' => 123];
			$plugin = mock();

			$chainedManager1 = mock(PM\PluginManagerInterface::class);
			$chainedManager1->shouldReceive('__invoke')->with($name, $options)->andThrow(PM\Exception\UnknownPlugin::class)->once();

			$chainedManager2 = mock(PM\PluginManagerInterface::class);
			$chainedManager2->shouldReceive('__invoke')->with($name, $options)->andReturn($plugin)->once();

			$manager = new PM\Chain([$chainedManager1, $chainedManager2]);
			expect($manager($name, $options))->toBe($plugin);
		});
		it('throws if no plugin managers in chain can create plugin', function ()
		{
			$name = 'test_plugin_name';
			$options = ['test' => 123];

			$chainedManager1 = mock(PM\PluginManagerInterface::class);
			$chainedManager1->shouldReceive('__invoke')->with($name, $options)->andThrow(PM\Exception\UnknownPlugin::class)->once();

			$chainedManager2 = mock(PM\PluginManagerInterface::class);
			$chainedManager2->shouldReceive('__invoke')->with($name, $options)->andThrow(PM\Exception\UnknownPlugin::class)->once();

			$exception = new PM\Exception\UnknownPlugin($name);

			$manager = new PM\Chain([$chainedManager1, $chainedManager2]);
			expect(fn () => $manager($name, $options))->toThrow($exception);
		});
	});
});
