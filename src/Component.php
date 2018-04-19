<?php

declare(strict_types=1);

namespace MyComponent;

use Keboola\Component\BaseComponent;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class Component extends BaseComponent
{
    public function run(): void
    {
        $this->moveFolder("files");
        $this->moveFolder("tables");
    }

    private function moveFolder(string $dataFolderName): void
    {
        $finder = new Finder();
        /**
         * @var Config $config
         */
        $config = $this->getConfig();

        $finder
            ->files()
            ->notName('*.manifest')
            ->name($config->getMask())
            ->in($this->getDataDir() . '/in/' . $dataFolderName);

        $fs = new Filesystem();
        foreach ($finder as $file) {
            $fs->mkdir($this->getDataDir() . "/out/" . $dataFolderName . "/" .  $file->getRelativePath());
            $fs->rename($file->getPathname(), $this->getDataDir() . "/out/" . $dataFolderName . "/" . $file->getRelativePathname());
            // move manifest file too
            if ($fs->exists($file->getPathname() . ".manifest")) {
                $fs->rename($file->getPathname() . ".manifest", $this->getDataDir() . "/out/" . $dataFolderName . "/" . $file->getRelativePathname() . ".manifest");
            }
            // move manifest from parent folder (sliced table)
            if ($fs->exists($file->getPath() . ".manifest")) {
                $fs->rename($file->getPath() . ".manifest", $this->getDataDir() . "/out/" . $dataFolderName . "/" . $file->getRelativePath() . ".manifest");
            }
        }
    }

    protected function getConfigClass(): string
    {
        return Config::class;
    }

    protected function getConfigDefinitionClass(): string
    {
        return ConfigDefinition::class;
    }
}
