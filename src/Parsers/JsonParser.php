<?php

declare(strict_types=1);

namespace Eva\DtoCreator\Parsers;

use Eva\DtoCreator\Schema\DtoSchema;
use Eva\DtoCreator\Schema\PropertySchema;
use Eva\Filesystem\Filesystem;

readonly class JsonParser
{
    public function __construct(
        private string $sourcePath,
    ) {}

    public function parse(): array
    {
        $filesystem = new Filesystem();
        $dtoSchemaList = json_decode($filesystem->fileGetContents($this->sourcePath), true);

        foreach ($dtoSchemaList as &$dtoSchema) {
            $dtoSchema['properties'] = $this->getProperties($dtoSchema['properties']);
            $dtoSchema = $this->getDtoSchema($dtoSchema);
        }

        return $dtoSchemaList;
    }

    private function getDtoSchema(array $dtoSchema): DtoSchema
    {
        return new DtoSchema($dtoSchema['namespace'], $dtoSchema['class'], $dtoSchema['properties']);
    }

    private function getProperties(array $properties): array
    {
        $propertySchemaList = [];

        foreach ($properties as $name => $type) {
            $propertySchemaList[] = new PropertySchema($name, $type);
        }

        return $propertySchemaList;
    }
}
