<?php

namespace bxrocketeer;

use Rocketeer\Abstracts\AbstractPlugin;
use Rocketeer\Services\TasksHandler;
use bxrocketeer\tasks\CheckBitrixDeploy;
use bxrocketeer\tasks\SetComposerAsExecutable;
use bxrocketeer\tasks\CleanRockteerData;
use bxrocketeer\tasks\CleanBitrixCache;

/**
 * Плагин, который регистрирует все таскидля упрощения деплоя на битриксе.
 */
class Registrator extends AbstractPlugin
{
    /**
     * @var array
     */
    protected $events = [
        [
            'event' => 'before',
            'task' => 'dependencies',
            'handler_class' => SetComposerAsExecutable::class,
        ],
        [
            'event' => 'after',
            'task' => 'setup',
            'handler_class' => '\\bxrocketeer\\AutoCreateShared',
        ],
        [
            'event' => 'after',
            'task' => 'setup',
            'handler_class' => '\\bxrocketeer\\Git',
        ],
        [
            'event' => 'after',
            'task' => 'deploy',
            'handler_class' => CleanRockteerData::class,
        ],
        [
            'event' => 'before',
            'task' => 'dependencies',
            'handler_class' => CleanBitrixCache::class,
        ],
        [
            'event' => 'before',
            'task' => 'primer',
            'handler_class' => CheckBitrixDeploy::class,
        ],
    ];

    /**
     * @inheritdoc
     */
    public function onQueue(TasksHandler $queue)
    {
        foreach ($this->events as $event) {
            $queue->addTaskListeners(
                $event['task'],
                $event['event'],
                $event['handler_class'],
                -10,
                true
            );
        }
    }
}
