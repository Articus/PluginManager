<?php
declare(strict_types=1);

use Articus\PluginManager as PM;
use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\ServiceLocatorInterface;

describe(PM\Laminas::class, function ()
{
	afterEach(function ()
	{
		Mockery::close();
	});
	context('->__invoke', function ()
	{
		it('creates plugin by name', function ()
		{
			skipIf(!class_exists(AbstractPluginManager::class));

			$name = 'test_plugin_name';
			$options = ['test' => 123];
			$plugin = mock();
			$laminasManager = mock(ServiceLocatorInterface::class);
			$laminasManager->shouldReceive('has')->with($name)->andReturnTrue()->once();
			$laminasManager->shouldReceive('build')->with($name, $options)->andReturn($plugin)->once();

			$manager = new PM\Laminas($laminasManager);
			expect($manager($name, $options))->toBe($plugin);
		});
		it('throws on unknown name', function ()
		{
			skipIf(!class_exists(AbstractPluginManager::class));

			$name = 'test_plugin_name';
			$options = ['test' => 123];
			$laminasManager = mock(ServiceLocatorInterface::class);
			$laminasManager->shouldReceive('has')->with($name)->andReturnFalse()->once();
			$exception = new PM\Exception\UnknownPlugin($name);

			$manager = new PM\Laminas($laminasManager);
			expect(fn () => $manager($name, $options))->toThrow($exception);
		});
	});
});
