<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__,
    ]);

    $rectorConfig->skip([
        __DIR__ . '/vendor',
        __DIR__ . '/.git',
        __DIR__ . '/var',
        __DIR__ . '/.omp',
        // ReturnNeverTypeRector breaks PHP's LSP check on legacy
        // inheritance chains: parent gains `: never`, children without it
        // become fatal ("must be compatible with ...: never").
        \Rector\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector::class,
    ]);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_85,
        SetList::CODE_QUALITY,
    ]);
};
