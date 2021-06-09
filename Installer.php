<?php

use Composer\Composer;
use Composer\Factory;
use Composer\Script\Event;
use Composer\IO\IOInterface;
use Composer\Package\Link;
use Composer\Package\Version\VersionParser;
use Composer\Json\JsonFile;

/**
 * Class Installer
 */
final class Installer
{
    private IOInterface $io;
    private Composer $composer;
    private JsonFile $composerJson;

    private array $packageRequires;
    private array $composerDefinitions;

    public static function install(Event $event)
    {
        $installer = new self($event->getComposer(), $event->getIO());
        
        $found = $installer->composer->getRepositoryManager()
            ->findPackages('psr/http-message-implementation', '1.0');
        
        $chooses = ['nyholm/psr7', 'laminas/laminas-diactoros'];
        $packages = [['nyholm/psr7', '^1.4.0'], ['laminas/laminas-diactoros', '^2.6.0']];
        
        foreach ($found as $package)
        {
            if ($package->getName() == 'nyholm/psr7' || $package->getName() == 'laminas/laminas-diactoros')
            {
                continue;
            }
            
            $chooses[] = $package->getName();
            $packages[] = [$package->getName(), $package->getVersion()];
            
            if (count($chooses) >= 5)
            {
                break;
            }
        }

        $result = (int) $installer->io->select('Choose Psr-7 implementation', $chooses, 0);
        $installer->addPackage($packages[$result][0], $packages[$result][1]);

        $installer->updateRootPackage();
        $installer->writeComposerJson();

        $projectRoot = realpath(dirname($installer->composerJson->getPath()));

        if ($installer->isWin())
        {
            @file_put_contents($projectRoot . '\console.cmd', 'php bin\console');
        }

        unlink($projectRoot . '\Installer.php');
    }

    private function __construct(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer; $this->io = $io;
        $this->packageRequires = $this->composer->getPackage()->getRequires();
        $this->composerDefinitions = ($this->composerJson = new JsonFile(Factory::getComposerFile()))->read();
    }

    private function createPackageLink(string $name, string $version): Link
    {
        return new Link('__root__', $name, (new VersionParser())->parseConstraints($version), 'requires', $version);
    }

    private function addPackage(string $name, string $version): void
    {
        $this->composerDefinitions['require'][$name] = $version;
        $this->packageRequires[$name] = $this->createPackageLink($name, $version);
    }

    private function updateRootPackage(): void
    {
        ($package = $this->composer->getPackage())
            ->setRequires($this->packageRequires);

        $this->composer->setPackage($package);
    }

    private function writeComposerJson(): void
    {
        unset($this->composerDefinitions['scripts']);
        $this->composerJson->write($this->composerDefinitions);
    }

    private function isWin(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}
