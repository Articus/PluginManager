<?php
declare(strict_types=1);

use Articus\PluginManager as PM;
use Psr\Container\ContainerInterface;
use spec\Example;

describe(PM\Factory\InvokablePlugin::class, function ()
{
	afterEach(function ()
	{
		Mockery::close();
	});
	context('->__invoke', function ()
	{
		it('creates instance of invokable plugin and passes options to constructor', function ()
		{
			$container = mock(ContainerInterface::class);
			$name = 'test_plugin_name';
			$options = ['test' => 123];

			$factory = new PM\Factory\InvokablePlugin(Example\InvokableService::class);
			$plugin = $factory($container, $name, $options);
			expect($plugin)->toBeAnInstanceOf(Example\InvokableService::class);
			if ($plugin instanceof Example\InvokableService)
			{
				expect($plugin->getOptions())->toBe($options);
			}
		});
	});
});
