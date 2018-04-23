<?php

declare(strict_types=1);

namespace Keboola\Processor\FilterFiles;

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
            $destination = $this->getDataDir() . "/out/" . $dataFolderName . "/";
            $fs->mkdir($destination . $file->getRelativePath());
            $fs->rename(
                $file->getPathname(),
                $destination . $file->getRelativePathname()
            );
            $manifest = $file->getPathname() . ".manifest";
            if ($fs->exists($manifest)) {
                $fs->rename(
                    $manifest,
                    $destination . $file->getRelativePathname() . ".manifest"
                );
            }
            $parentFolderManifest = $file->getPath() . ".manifest";
            if ($fs->exists($parentFolderManifest)) {
                $fs->rename(
                    $parentFolderManifest,
                    $destination . $file->getRelativePath() . ".manifest"
                );
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
