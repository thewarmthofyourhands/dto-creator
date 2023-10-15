<?php

declare(strict_types=1);

namespace Eva\DtoCreator\Tmp;

readonly class PropertyTmp
{
    public function __construct(
        private string $name,
        private string $type,
    ) {}

    public function createTmp(): string
    {
        return <<<EOF
                private {$this->type} \${$this->name},
        EOF;
    }
}
