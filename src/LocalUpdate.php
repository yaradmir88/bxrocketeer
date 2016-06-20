<?php

namespace bxrocketeer;

class LocalUpdate extends \Rocketeer\Abstracts\AbstractTask
{
    protected $local = true;
    /**
     * @var string
     */
    protected $description = 'Updates developer\'s local data';


    /**
     * @return void
     */
    public function execute()
    {
        $localPath = realpath($this->paths->getConfigurationPath() . '/../');
        $this->command->info($this->description);
        $this->run("cd {$localPath}");
        $this->run("git pull");
        $this->run("php {$localPath}/composer.phar install --no-interaction");
        $migrations = (bool) $this->rocketeer->getOption('strategies.migrate');
        if ($migrations) {
            $this->run('php cli.php bxmigrate:up');
        }
    }
}
