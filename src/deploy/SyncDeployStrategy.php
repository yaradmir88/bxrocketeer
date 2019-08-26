<?php

namespace bxrocketeer\deploy;

use Rocketeer\Bash;
use Rocketeer\Strategies\Deploy\SyncStrategy;

/**
 * Деплой данных с помощью rsync.
 */
class SyncDeployStrategy extends SyncStrategy
{
    /**
     * @inheritdoc
     */
    protected function rsyncTo($destination, $source = './')
    {
        // Build host handle
        $arguments = [];
        $handle = $this->getSyncHandle();

        // Create options
        $options = ['--recursive' => null, '--rsh' => 'ssh', '--compress' => null];

        // Create SSH command
        $options['--rsh'] = $this->getTransport() . ' -o StrictHostKeyChecking=no';

        // Build arguments
        $arguments[] = $source;
        $arguments[] = $handle . ':' . $destination;

        // Set excluded files and folders
        $options['--exclude'] = [
            '.git',
            '.rocketeer',
            '.php_cs.cache',
            '.vagrant',
            'frontend',
            'upload',
            'bitrix/.settings.php',
            'bitrix/php_interface/dbconn.php',
            'bitrix/cache',
            'bitrix/managed_cache',
            'bitrix/stack_cache',
        ];

        // Create binary and command
        $rsync = $this->binary('rsync');
        $command = $rsync->getCommand(null, $arguments, $options);

        return $this->bash->onLocal(function (Bash $bash) use ($command) {
            return $bash->run($command);
        });
    }
}
