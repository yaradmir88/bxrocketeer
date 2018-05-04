<?php

namespace bxrocketeer;

use Rocketeer\Abstracts\AbstractPlugin;
use Rocketeer\Services\TasksHandler;

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
            'handler_class' => '\\bxrocketeer\\tasks\\SetComposerAsExecutable',
        ],
        [
            'event' => 'after',
            'task' => 'setup',
            'handler_class' => '\\bxrocketeer\\tasks\\CreateShared',
        ],
        [
            'event' => 'after',
            'task' => 'setup',
            'handler_class' => '\\bxrocketeer\\tasks\\PrepareSshForGit',
        ],
        [
            'event' => 'after',
            'task' => 'deploy',
            'handler_class' => '\\bxrocketeer\\tasks\\CleanRockteerData',
        ],
        [
            'event' => 'after',
            'task' => 'dependencies',
            'handler_class' => '\\bxrocketeer\\tasks\\CleanBitrixCache',
        ],
        [
            'event' => 'before',
            'task' => 'primer',
            'handler_class' => '\\bxrocketeer\\tasks\\CheckBitrixDeploy',
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
