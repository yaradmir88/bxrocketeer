<?php

namespace bxrocketeer\migrations;

use Rocketeer\Abstracts\Strategies\AbstractStrategy;
use Rocketeer\Interfaces\Strategies\MigrateStrategyInterface;

/**
 * Стратегия для миграций, которая использует marvin255/bxmigrate.
 */
class BitrixMigrateStrategy extends AbstractStrategy implements MigrateStrategyInterface
{
    /**
     * @var string
     */
    protected $description = 'Migrates your database with marvin255/bxmigrate';

    /**
     * @inheritdoc
     */
    public function isExecutable()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function migrate()
    {
        $this->runForCurrentRelease([
            $this->php()->getCommand('-d short_open_tag=On -f cli.php bxmigrate:up'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function seed()
    {
        return $this->migrate();
    }
}
