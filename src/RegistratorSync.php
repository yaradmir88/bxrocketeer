<?php

namespace bxrocketeer;

use Rocketeer\Services\TasksHandler;

class RegistratorSync extends Registrator
{
    /**
     * @inheritdoc
     */
    public function onQueue(TasksHandler $queue)
    {
        //подключаем обработчики событий
        $events = [
            //создаем shared папки
            [
                'event' => 'after',
                'task' => 'setup',
                'handler_class' => '\\bxrocketeer\\AutoCreateShared',
            ],
            //настраиваем репу
            [
                'event' => 'after',
                'task' => 'setup',
                'handler_class' => '\\bxrocketeer\\Git',
            ],
            //очищаем битриксовый кэш
            [
                'event' => 'before',
                'task' => 'dependencies',
                'handler_class' => '\\bxrocketeer\\ClearBitrixCache',
            ],
            //дополнительные проверки для рокетира
            [
                'event' => 'before',
                'task' => 'primer',
                'handler_class' => '\\bxrocketeer\\CheckBitrixDeploy',
            ],
        ];
        foreach ($events as $event) {
            $this->registerEvent($event, $queue);
        }
    }
}
