<?php

namespace bxrocketeer;

class ClearBitrixCache extends \Rocketeer\Abstracts\AbstractTask
{
    /**
     * @var string
     */
    protected $description = 'Clear bitrix cache';

    public function execute()
    {
        $this->command->info($this->description);

        return $this->runForCurrentRelease([
            'rm -Rf web/bitrix/cache/*',
            'rm -Rf web/bitrix/managed_cache/*',
        ]);
    }
}
