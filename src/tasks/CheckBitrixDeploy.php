<?php

namespace bxrocketeer\tasks;

use Rocketeer\Abstracts\AbstractTask;

/**
 * Проверяет можно ли запустить деплой на данной локальной машине.
 */
class CheckBitrixDeploy extends AbstractTask
{
    /**
     * @var string
     */
    protected $description = 'Check whether we can or not continue deploy';

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $result = true;
        $overload = ini_get('mbstring.func_overload');

        if ($overload !== '0') {
            $this->command->error(
                'mbstring.func_overload is set to ' . $overload
                . '. Change it to 0 in your php.ini: ' . php_ini_loaded_file()
                . ' or run rocketeer like so: php -f rocketeer.phar -d mbstring.func_overload=0 update'
            );
            $this->halt();
            $result = false;
        }

        return $result;
    }
}
