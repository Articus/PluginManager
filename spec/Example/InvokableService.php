<?php
declare(strict_types=1);

namespace spec\Example;

class InvokableService
{
	protected array $options;

	public function __construct(array $options)
	{
		$this->options = $options;
	}

	public function getOptions(): array
	{
		return $this->options;
	}
}
