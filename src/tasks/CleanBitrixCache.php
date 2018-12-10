<?php

namespace bxrocketeer\tasks;

use Rocketeer\Abstracts\AbstractTask;

/**
 * Очищает кэш битрикса.
 */
class CleanBitrixCache extends AbstractTask
{
    /**
     * @var string
     */
    protected $description = 'Cleaning bitrix cache';

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->command->info($this->description);

        $clearCommands = [
            'rm -Rf web/bitrix/cache/*',
            'rm -Rf web/bitrix/managed_cache/*',
        ];

        $releasePath = rtrim($this->releasesManager->getCurrentReleasePath(), '/');
        $getListCommand = $this->php()->getCommand("-d short_open_tag=On -f {$releasePath}/cli.php list");
        $listOfAviableCommands = $this->runRaw($getListCommand);
        if (mb_strpos($listOfAviableCommands, 'base:cache.clear') !== false) {
            $clearCacheCommand = $this->php()->getCommand('-d short_open_tag=On -f cli.php base:cache.clear --quiet');
            array_unshift($clearCommands, $clearCacheCommand);
        }

        return $this->runForCurrentRelease($clearCommands);
    }
}
