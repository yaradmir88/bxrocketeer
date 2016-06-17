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
        $this->command->info($this->description);
        $basePath = $this->paths->getHomeFolder() . '/shared';
        $shareds = $this->rocketeer->getOption('remote.shared');
        //folders
        foreach ($shareds as $object) {
            $arObject = pathinfo($object);
            if (!empty($arObject['extension'])) $object = $arObject['dirname'];
            $arPath = explode('/', trim($object, "/ \t\n\r\0\x0B"));
            $checkedPath = '';
            foreach ($arPath as $chain) {
                $fullPath = "{$basePath}{$checkedPath}/{$chain}";
                $isFolderExists = $this->fileExists($fullPath);
                if (!$isFolderExists) {
                    $this->createFolder($fullPath);
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
            if ($this->fileExists($fullPath)) continue;
            $this->run("touch '{$fullPath}'");
            //$local = __DIR__ . '/../examples/' . $arObject['basename'];
            /*
            if (file_exists($local)) {
                $content = file_get_contents($local);
                $this->runRaw('echo "' . addcslashes($content, '"$') . '" > "'. $fullPath . '"');
            }
            */
        }
        return true;
    }
}
