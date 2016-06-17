<?php

namespace bxrocketeer;

class Registrator
{
    /**
     * Регистрирует события для данной библиотеки
     */
    public static function events($priority = 0)
    {
        $events = [
            //делаем композер исполняемым из-за дурацкой винды
            [
                'type' => 'before',
                'name' => 'dependencies',
                'handler_class' => '\\bxrocketeer\\SetComposerAsExecutable',
            ],
            //создаем shared папки
            [
                'type' => 'after',
                'name' => 'setup',
                'handler_class' => '\\bxrocketeer\\AutoCreateShared',
            ],
            //настраиваем репу
            [
                'type' => 'after',
                'name' => 'setup',
                'handler_class' => '\\bxrocketeer\\Git',
            ],
            //удаляем следы роекетира с хоста
            [
                'type' => 'after',
                'name' => 'deploy',
                'handler_class' => '\\bxrocketeer\\CleanRockteerData',
            ],
            //удаляем следы роекетира с хоста
            [
                'type' => 'after',
                'name' => 'update',
                'handler_class' => '\\bxrocketeer\\CleanRockteerData',
            ],
            //очищаем битриксовый кэш
            [
                'type' => 'after',
                'name' => 'update',
                'handler_class' => '\\bxrocketeer\\ClearBitrixCache',
            ],
        ];
        foreach ($events as $event) {
            self::registerEvent($event, $priority);
        }
    }

    /**
     * Регистрирует событие
     */
    protected static function registerEvent(array $event, $priority)
    {
        if (empty($event['type']) || empty($event['name']) || empty($event['handler_class'])) {
            return null;
        }
        if ($event['type'] === 'before') {
            Rocketeer::before($event['name'], $event['handler_class'], $priority);
        } else {
            Rocketeer::after($event['name'], $event['handler_class'], $priority);
        }
    }
}
