<?php

namespace bxrocketeer;

class CheckBitrixDeploy extends \Rocketeer\Abstracts\AbstractTask
{
    /**
     * @var string
     */
    protected $description = 'Check whether we can or on continue deploy';

    public function execute()
    {
        $overload = ini_get('mbstring.func_overload');
        if ($overload !== '0') {
            $this->command->error(
                'mbstring.func_overload is set to '.$overload.'. Change it to 0 in your php.ini.'
            );
            $this->halt();

            return false;
        }

        return true;
    }
}
