<?php

declare(strict_types=1);

namespace Eva\DtoCreator\Parsers;

use Eva\DtoCreator\Schema\DtoSchema;
use Eva\DtoCreator\Schema\PropertySchema;
use Symfony\Component\Yaml\Yaml;

readonly class YamlParser
{
    public function __construct(
        private string $sourcePath,
    ) {}

    public function parse(): array
    {
        $dtoSchemaList = Yaml::parseFile($this->sourcePath);

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
