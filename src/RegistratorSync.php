<?php

namespace bxrocketeer;

/**
 * Плагин, который регистрирует все таскидля упрощения деплоя на битриксе
 * с помощью rsync.
 */
class RegistratorSync extends Registrator
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
            'task' => 'dependencies',
            'handler_class' => '\\bxrocketeer\\tasks\\CleanBitrixCache',
        ],
    ];
}
