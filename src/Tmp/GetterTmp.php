<?php

declare(strict_types=1);

namespace Eva\DtoCreator\Tmp;

class GetterTmp
{
    public function __construct(
        private readonly string $name,
        private readonly string $type,
    ) {}

    private function getFunctionName(): string
    {
        return 'get' . ucfirst($this->name);
    }

    public function createTmp(): string
    {
        return <<<EOF
            public function {$this->getFunctionName()}(): {$this->type}
            {
                return \$this->{$this->name};
            }
        EOF;
    }
}
