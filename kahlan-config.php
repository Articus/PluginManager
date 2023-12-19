<?php
declare(strict_types=1);

use Kahlan\Filter\Filters;

/** @var \Kahlan\Cli\Kahlan $this  */
/** @var \Kahlan\Cli\CommandLine $cli */
$cli = $this->commandLine();

//Switch to Mockery for stubbing and mocking
$cli->set('include', []);
Filters::apply($this, 'run', function ($next)
{
	Mockery::globalHelpers();
	return $next();
});
//Register simple helper for object introspection
if (!function_exists('getProtectedProperty'))
{
	function getProtectedProperty(object $object, string $property)
	{
		$classReflection = new ReflectionClass($object);
		$propertyReflection = $classReflection->getProperty($property);
		$propertyReflection->setAccessible(true);
		return $propertyReflection->getValue($object);
	}
}

//Update Kahlan default CLI options
$cli->option('grep', 'default', '*.spec.php');
$cli->option('reporter', 'default', 'verbose');
$cli->option('coverage', 'default', 3);
$cli->option('clover', 'default', 'spec_output/kahlan.coverage.xml');
