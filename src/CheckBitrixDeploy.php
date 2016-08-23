<?php

namespace bxrocketeer;

class CheckBitrixDeploy extends \Rocketeer\Abstracts\AbstractTask
{
	/**
	 * @var string
	 */
	protected $description = 'Check whether we can or on continue deploy';


	/**
	 * @return void
	 */
	public function execute()
	{
		if (ini_get('mbstring.func_overload') !== '0') {
			$this->command->error('Внимание, в php.ini установлен mbstring.func_overload, деплой работать не будет.');
			$this->halt();
			return false;
		}
		return true;
	}
}
