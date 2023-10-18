<?php

declare(strict_types=1);

namespace Eva\DtoCreator\Tmp;

readonly class DtoTmp
{
    public function __construct(
        private string $namespace,
        private string $class,
        private array $propertylist,
        private array $getterList,
    ) {}

    private function getPropertiesTmp(): string
    {
        return implode(
            PHP_EOL,
            array_map(fn(PropertyTmp $property) => $property->createTmp(), $this->propertylist),
        );
    }

    private function getGettersTmp(): string
    {
        return implode(
            PHP_EOL . PHP_EOL,
            array_map(fn(GetterTmp $getter) => $getter->createTmp(), $this->getterList),
        );
    }

    public function createTmp(): string
    {
        return  <<<EOF
        <?php
        
        declare(strict_types=1);
        
        namespace {$this->namespace};
        
        readonly class {$this->class}
        {
            public function __construct(
        {$this->getPropertiesTmp()}
            ) {}
        
        {$this->getGettersTmp()}
        }

        EOF;
    }
}
