<?php
declare(strict_types=1);

namespace Articus\PluginManager\Exception;

use Exception;
use function sprintf;

class UnknownPlugin extends Exception
{
	public function __construct(string $pluginName)
	{
		$message = sprintf('Unknown plugin "%s".', $pluginName);
		parent::__construct($message);
	}
}
