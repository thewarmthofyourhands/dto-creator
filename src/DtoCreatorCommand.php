<?php

declare(strict_types=1);

namespace Eva\DtoCreator;

use Eva\Console\ArgvInput;
use InvalidArgumentException;

class DtoCreatorCommand
{
    public function execute(ArgvInput $argvInput): void
    {
        if (false === isset($argvInput->getOptions()['sourcePath'])) {
            throw new InvalidArgumentException('sourcePath is required option');
        }

        $sourcePath = $argvInput->getOptions()['sourcePath'];
        $baseNamespace = $argvInput->getOptions()['baseNamespace'] ?? 'App';
        $baseDir = $argvInput->getOptions()['baseDir'] ?? 'src';
        $creator = new Creator($baseNamespace, $baseDir, $sourcePath);
        $creator->create();
    }
}
