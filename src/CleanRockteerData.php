<?php

namespace bxrocketeer;

class CleanRockteerData extends \Rocketeer\Abstracts\AbstractTask
{
	/**
	 * @var string
	 */
	protected $description = 'Remove rocketeer info files';


	/**
	 * @return void
	 */
	public function execute()
	{
		$this->command->info($this->description);
		return $this->runForCurrentRelease([
			'rm -Rf .rocketeer',
			'rm -Rf rockteer.phar',
		]);
	}
}
