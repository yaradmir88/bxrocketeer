<?php

namespace bxrocketeer\tasks;

use Rocketeer\Abstracts\AbstractTask;

/**
 * Автоматически создает shared папки и файлы на хосте.
 */
class CreateShared extends AbstractTask
{
    /**
     * @var string
     */
    protected $description = 'Automatically creation of shared files and folders';

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $remoteSharedPath = rtrim($this->paths->getHomeFolder(), '/') . '/shared';
        $localPath = rtrim($this->paths->getConfigurationPath(), '/');
        $shareds = array_map('trim', $this->rocketeer->getOption('remote.shared'));

        //folders
        foreach ($shareds as $sharedItem) {
            $path = pathinfo($sharedItem);
            $sharedFolder = !empty($path['extension']) ? $path['dirname'] : $sharedItem;
            $sharedFolder = rtrim($sharedFolder, '/');

            $remoteSharedFolder = $remoteSharedPath . '/' . $sharedFolder;
            $isRemoteSharedFolderExists = trim($this->runRaw("[ -d '{$remoteSharedFolder}' ] && echo 1")) === '1';

            if (!$isRemoteSharedFolderExists) {
                $this->run("mkdir -p '{$remoteSharedFolder}'");
            }
        }

        //files
        foreach ($shareds as $sharedItem) {
            $path = pathinfo($sharedItem);
            if (empty($path['extension'])) {
                continue;
            }

            $remoteSharedFile = $remoteSharedPath . '/' . $sharedItem;
            $isRemoteSharedFileExists = trim($this->runRaw("[ -f '{$remoteSharedFile}' ] && echo 1")) === '1';

            if (!$isRemoteSharedFileExists) {
                $fileContent = '';
                $localSharedFile = "{$localPath}/../examples/{$path['basename']}";
                if (file_exists($localSharedFile)) {
                    $fileContent = addcslashes(file_get_contents($localSharedFile), '"$');
                }
                $this->run("touch '{$remoteSharedFile}'");
                $this->runRaw("echo \"{$fileContent}\" > \"{$remoteSharedFile}\"");
            }
        }
    }
}
