<?php

namespace bxrocketeer;

use Rocketeer\Abstracts\AbstractPlugin;
use Rocketeer\Services\TasksHandler;

class Registrator extends AbstractPlugin
{
	/**
	 * Регистрирует события для данной библиотеки
	 */
	public function onQueue(TasksHandler $queue)
	{
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
				'event' => 'after',
				'task' => 'update',
				'handler_class' => '\\bxrocketeer\\ClearBitrixCache',
			],
		];
		foreach ($events as $event) {
			$this->registerEvent($event, $queue);
		}
	}

	/**
	 * Регистрирует событие
	 */
	protected function registerEvent(array $event, TasksHandler $queue)
	{
		if (empty($event['event']) || empty($event['task']) || empty($event['handler_class'])) {
			return null;
		}
		$queue->addTaskListeners($event['task'], $event['event'], $event['handler_class'], -10, true);
	}
}
