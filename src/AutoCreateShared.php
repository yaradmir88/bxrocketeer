<?php

namespace bxrocketeer;

class AutoCreateShared extends \Rocketeer\Abstracts\AbstractTask
{
    /**
     * @var string
     */
    protected $description = 'Automatically creation of shared files and folders';


    /**
     * @return void
     */
    public function execute()
    {
        $task = $this;
        $basePath = $task->paths->getHomeFolder() . '/shared';
        $localPath = $task->paths->getRocketeerConfigFolder();
        $shareds = $task->rocketeer->getOption('remote.shared');
        //folders
        foreach ($shareds as $object) {
            $arObject = pathinfo($object);
            if (!empty($arObject['extension'])) $object = $arObject['dirname'];
            $arPath = explode('/', trim($object, "/ \t\n\r\0\x0B"));
            $checkedPath = '';
            foreach ($arPath as $chain) {
                $fullPath = "{$basePath}{$checkedPath}/{$chain}";
                $isFolderExists = trim($task->runRaw('[ -d \'' . $fullPath . '\' ] && echo 1')) === '1';
                if (!$isFolderExists) {
                    $task->run("mkdir '{$fullPath}'");
                }
                $checkedPath .= "/{$chain}";
            }
        }
        //files
        foreach ($shareds as $object) {
            $arObject = pathinfo($object);
            if (empty($arObject['extension'])) continue;
            $object = trim($object, "/ \t\n\r\0\x0B");
            $fullPath = "{$basePath}/{$object}";
            if (trim($task->runRaw('[ -f \'' . $fullPath . '\' ] && echo 1')) === '1') continue;
            $task->run("touch '{$fullPath}'");
            $local = $localPath . '/../examples/' . $arObject['basename'];
            if (file_exists($local)) {
                $content = file_get_contents($local);
                $task->runRaw('echo "' . addcslashes($content, '"$') . '" > "'. $fullPath . '"');
            }
        }
    }
}
