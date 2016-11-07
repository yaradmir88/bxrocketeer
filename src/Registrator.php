<?php

namespace bxrocketeer;

use Rocketeer\Abstracts\AbstractPlugin;
use Rocketeer\Services\TasksHandler;

class Registrator extends AbstractPlugin
{
    /**
     * Регистрирует события для данной библиотеки.
     */
    public function onQueue(TasksHandler $queue)
    {
        //регистрируем миграции для битрикса
        $queue->config->set(
            'strategies.Migrate',
            '\bxrocketeer\migrations\BitrixMigrateStrategy'
        );
        echo 1111111111111111111111; die();
        //var_dump($queue->rocketeer->config->get('strategies.migrate')); die();
        //подключаем обработчики событий
        $events = [
            //делаем композер исполняемым из-за дурацкой винды
            [
                'event' => 'before',
                'task' => 'dependencies',
                'handler_class' => '\\bxrocketeer\\SetComposerAsExecutable',
            ],
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
            //удаляем следы роекетира с хоста
            [
                'event' => 'after',
                'task' => 'deploy',
                'handler_class' => '\\bxrocketeer\\CleanRockteerData',
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

    /**
     * Регистрирует событие.
     */
    protected function registerEvent(array $event, TasksHandler $queue)
    {
        if (empty($event['event']) || empty($event['task']) || empty($event['handler_class'])) {
            return null;
        }
        $queue->addTaskListeners($event['task'], $event['event'], $event['handler_class'], -10, true);
    }
}
