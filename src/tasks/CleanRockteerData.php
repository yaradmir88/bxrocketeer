<?php

namespace bxrocketeer\tasks;

use Rocketeer\Abstracts\AbstractTask;

/**
 * Удаляет папку с настройками рокетира на площадке после деплоя.
 */
class CleanRockteerData extends AbstractTask
{
    /**
     * @var string
     */
    protected $description = 'Remove rocketeer info files';

    /**
     * @inheritdoc
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
