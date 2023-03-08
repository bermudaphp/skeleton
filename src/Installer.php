<?php

use Composer\Composer;
use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Package\Link;
use Composer\Package\Version\VersionParser;
use Composer\Script\Event;
use Webimpress\SafeWriter\FileWriter;

final class Installer
{
    private const packages = [
        'nyholm/psr7',
        'laminas/laminas-diactoros',
        'guzzlehttp/psr7',
        'slim/psr7',
    ];
    private IOInterface $io;
    private Composer $composer;
    private JsonFile $composerJson;
    private array $packageRequires;
    private array $composerDefinitions;

    private function __construct(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
        $this->packageRequires = $this->composer->getPackage()->getRequires();
        $this->composerDefinitions = ($this->composerJson = new JsonFile(Factory::getComposerFile()))->read();
    }

    public static function install(Event $event): void
    {
        $installer = new Installer($event->getComposer(), $event->getIO());
        $installer->selectPsr7Implementation();

        $answer = $installer->io->askConfirmation('Do you want to install the template engine ? (y/n) ', false);

        if ($answer) {
            $package = $installer->composer->getRepositoryManager()
                ->findPackage('bermudaphp/templater', '*');
            $installer->addPackage($package->getName(), $package->getVersion());
        }

        $installer->updateRootPackage();
        $installer->writeComposerJson();

        $projectRoot = getcwd(); //realpath(dirname($installer->composerJson->getPath()));

        if ($installer->isWin()) {
            FileWriter::writeFile($projectRoot . '\console.cmd', '@echo OFF & php bin\console %*');
        }

        if ($answer) {
            $installer->writeConfig('Bermuda\Templater\ConfigProvider');
        }

        mkdir("$projectRoot\logs", 0600);
        mkdir("$projectRoot\config\cache", 0600);

        unlink(__FILE__);
    }

    private function writeConfig(string $provider): void
    {
        $contents = file_get_contents(getcwd() . '/config/config.php');
        $contents = substr($contents, 0,
                ($offset = strpos($contents, 'Config::merge(') + strlen('Config::merge('))
            ) . PHP_EOL . '    new ' . $provider . '(),' . substr($contents, $offset);

        FileWriter::writeFile(getcwd() . '/config/config.php', $contents);
    }

    private function selectPsr7Implementation(): void
    {
        $answer = (int)$this->io->select('Select PSR-7 implementation from the list. Default: nyholm/psr7', self::packages, 0);
        $package = $this->composer->getRepositoryManager()->findPackage(self::packages[$answer], '*');

        $this->addPackage($package->getName(), $package->getVersion());
    }

    private function addPackage(string $name, string $version): void
    {
        $this->composerDefinitions['require'][$name] = $version;
        $this->packageRequires[$name] = $this->createPackageLink($name, $version);
    }

    private function createPackageLink(string $name, string $version): Link
    {
        return new Link('__root__', $name, (new VersionParser)->parseConstraints($version), 'requires', $version);
    }

    private function updateRootPackage(): void
    {
        ($package = $this->composer->getPackage())->setRequires($this->packageRequires);
        $this->composer->setPackage($package);
    }

    private function writeComposerJson(): void
    {
        unset($this->composerDefinitions['scripts']['post-root-package-install']);
        $this->composerJson->write($this->composerDefinitions);
    }

    private function isWin(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}
