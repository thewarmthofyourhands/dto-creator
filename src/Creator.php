<?php

declare(strict_types=1);

namespace Eva\DtoCreator;

use Eva\DtoCreator\Parsers\JsonParser;
use Eva\DtoCreator\Parsers\YamlParser;
use Eva\DtoCreator\Schema\DtoSchema;
use Eva\DtoCreator\Schema\PropertySchema;
use Eva\DtoCreator\Tmp\DtoTmp;
use Eva\DtoCreator\Tmp\GetterTmp;
use Eva\DtoCreator\Tmp\PropertyTmp;
use Eva\Filesystem\Filesystem;

class Creator
{
    public function __construct(
        private readonly string $baseNamespace,
        private readonly string $baseDir,
        private readonly string $sourcePath,
    ) {}

    private function parseSource(): array
    {
        $ext = pathinfo($this->sourcePath, PATHINFO_EXTENSION);

        if ('yaml' === $ext) {
            return (new YamlParser($this->sourcePath))->parse();
        }

        if ('json' === $ext) {
            return (new JsonParser($this->sourcePath))->parse();
        }

        throw new \RuntimeException("Unknown $ext extension of source file");
    }

    public function create(): void
    {
        $dtoSchemaList = $this->parseSource();
        foreach ($dtoSchemaList as $dtoSchema) {
            $this->generateDto($dtoSchema);
        }
    }
    private function generateDto(DtoSchema $dtoSchema): void
    {
        $dtoTmp = $this->getDtoTmp($dtoSchema);
        $dirPath = $this->getPathByNamespace($dtoSchema->getNamespace());
        $this->writeDto(
            $dtoTmp->createTmp(),
            $dirPath,
            "{$dtoSchema->getClass()}.php",
        );
    }

    private function getDtoTmp(DtoSchema $dtoSchema): DtoTmp
    {
        $propertyTmpList = [];
        $getterTmpList = [];

        foreach ($dtoSchema->getProperties() as $propertySchema) {
            assert($propertySchema instanceof PropertySchema);
            $propertyTmpList[] = new PropertyTmp($propertySchema->getName(), $propertySchema->getType());
            $getterTmpList[] = new GetterTmp($propertySchema->getName(), $propertySchema->getType());
        }

        return new DtoTmp($dtoSchema->getNamespace(), $dtoSchema->getClass(), $propertyTmpList, $getterTmpList);
    }

    private function getPathByNamespace(string $namespace): string
    {
        $namespace = str_replace($this->baseNamespace, $this->baseDir, $namespace);

        return str_replace('\\', '/', $namespace);
    }

    private function writeDto(string $fileData, string $dirPath, string $fileName): void
    {
        $filesystem = new Filesystem();

        if (false === $filesystem->isDir($dirPath)) {
            $filesystem->mkdir($dirPath, 0755, true);
        }

        if ($filesystem->fileExists("$dirPath/$fileName")) {
            $filesystem->rm("$dirPath/$fileName");
        }

        $filesystem->filePutContents("$dirPath/$fileName", $fileData);
    }
}
