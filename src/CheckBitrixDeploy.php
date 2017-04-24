<?php

namespace bxrocketeer;

class CheckBitrixDeploy extends \Rocketeer\Abstracts\AbstractTask
{
    /**
     * @var string
     */
    protected $description = 'Check whether we can or not continue deploy';

    public function execute()
    {
        $overload = ini_get('mbstring.func_overload');
        if ($overload !== '0') {
            $this->command->error(
                'mbstring.func_overload is set to '.$overload.'. Change it to 0 in your php.ini: '.php_ini_loaded_file().' or run rocketeer like so: php -f rocketeer.phar -d mbstring.func_overload=0 update'
            );
            $this->halt();

            return false;
        }

        return true;
    }
}
