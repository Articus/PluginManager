<?php
declare(strict_types=1);

namespace Articus\PluginManager;

use Laminas\ServiceManager\ServiceLocatorInterface;

class Laminas implements PluginManagerInterface
{
	protected ServiceLocatorInterface $laminasServiceLocator;

	public function __construct(ServiceLocatorInterface $laminasServiceLocator)
	{
		$this->laminasServiceLocator = $laminasServiceLocator;
	}

	/**
	 * @inheritdoc
	 */
	public function __invoke(string $name, array $options)
	{
		if (!$this->laminasServiceLocator->has($name))
		{
			throw new Exception\UnknownPlugin($name);
		}
		return $this->laminasServiceLocator->build($name, $options);
	}
}
