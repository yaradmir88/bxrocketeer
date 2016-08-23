<?php

namespace bxrocketeer;

class SetComposerAsExecutable extends \Rocketeer\Abstracts\AbstractTask
{
	/**
	 * @var string
	 */
	protected $description = 'Make composer.phar executable';


	/**
	 * @return void
	 */
	public function execute()
	{
		$this->command->info($this->description);
		return $this->runForCurrentRelease('chmod 0770 composer.phar');
	}
}
