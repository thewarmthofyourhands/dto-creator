<?php

declare(strict_types=1);

namespace Eva\DtoCreator\Schema;

readonly class PropertySchema
{
    public function __construct(
        private readonly string $name,
        private readonly string $type,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }
    public function getType(): string
    {
        return $this->type;
    }
}
