<?php

declare(strict_types=1);

namespace Eva\DtoCreator\Schema;

readonly class DtoSchema
{
    public function __construct(
        private readonly string $namespace,
        private readonly string $class,
        private readonly array $properties,
    ) {}

    public function getNamespace(): string
    {
        return $this->namespace;
    }
    public function getClass(): string
    {
        return $this->class;
    }
    public function getProperties(): array
    {
        return $this->properties;
    }
}
