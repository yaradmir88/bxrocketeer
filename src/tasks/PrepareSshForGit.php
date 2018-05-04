<?php

namespace bxrocketeer\tasks;

use Rocketeer\Abstracts\AbstractTask;

/**
 * Генерирует и отображает ssh ключ для хоста.
 * Добавляет адрес репозитория в доверенные ssh хосты.
 */
class PrepareSshForGit extends AbstractTask
{
    /**
     * @var string
     */
    protected $description = 'Prepare hosting for working with git';

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->explainer->line($this->description);

        $pathToSshFolder = trim($this->runRaw('cd ~ && pwd')) . '/.ssh';
        $isSshFolderExists = trim($this->runRaw("[ -d {$pathToSshFolder} ] && echo 1")) === '1';
        if (!$isSshFolderExists) {
            $this->run("mkdir -p {$pathToSshFolder}");
        }

        $this->addGitToKnownHosts($pathToSshFolder);
        $this->createOrShowSshKey($pathToSshFolder);

        return true;
    }

    /**
     * Задает ссылку на репозиторий в список хостов, которым можно доверять.
     *
     * @param string $pathToSshFolder
     */
    protected function addGitToKnownHosts($pathToSshFolder)
    {
        $repo = $this->rocketeer->getOption('scm.repository');
        if (!$repo) {
            return null;
        }

        $arRepo = parse_url($repo);
        if (empty($arRepo['scheme']) || $arRepo['scheme'] !== 'ssh') {
            return null;
        }

        $isKnownHostsExists = trim($this->runRaw("[ -f {$pathToSshFolder}/known_hosts ] && echo 1")) === '1';
        if (!$isKnownHostsExists) {
            $this->run("echo \"\" > {$pathToSshFolder}/known_hosts");
        }

        $hostname = $arRepo['host'];
        $this->runForCurrentRelease([
            "ssh-keygen -R {$hostname}",
            'ssh-keyscan -t rsa' . (!empty($arRepo['port']) ? " -p{$arRepo['port']}" : '') . " -H {$hostname} >> {$pathToSshFolder}/known_hosts",
        ]);
    }

    /**
     * Выводит открытую часть ключа на экран, если ключа нет, то пробует создать.
     *
     * @param string $pathToSshFolder
     */
    protected function createOrShowSshKey($pathToSshFolder)
    {
        $isKeyExists = trim($this->runRaw("[ -f {$pathToSshFolder}/id_rsa.pub ] && echo 1")) === '1';
        if (!$isKeyExists) {
            $this->run("ssh-keygen -t rsa -N \"\" -f {$pathToSshFolder}/id_rsa");
        }

        $key = $this->runRaw("cat {$pathToSshFolder}/id_rsa.pub");

        $this->command->info('Hosting ssh key is: ' . $key);
    }
}
