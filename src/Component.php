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

    /**
     * @param $dataFolderName string
     */
    private function moveFolder($dataFolderName): void
    {
        $finder = new Finder();
        /**
         * @var $config Config
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
