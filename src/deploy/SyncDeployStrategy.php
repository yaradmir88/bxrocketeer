<?php

namespace bxrocketeer\deploy;

use Rocketeer\Strategies\Deploy\SyncStrategy;

/**
 * Расширяем миграцию с помощью rsync.
 */
class SyncDeployStrategy extends SyncStrategy
{
    /**
     * @var array
     */
    protected $options = [
        'excluded' => ['.git'],
    ];
}
