<?php

namespace bxrocketeer\tasks;

/**
 * Очищает кэш битрикса.
 */
class CleanBitrixCache extends \Rocketeer\Abstracts\AbstractTask
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
            $clearCommands[] = $this->php()->getCommand('-d short_open_tag=On -f cli.php base:cache.clear --quiet');
        }

        return $this->runForCurrentRelease($clearCommands);
    }
}
