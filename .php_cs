<?php

$header = <<<EOF
This file is part of the SomeWork/OffsetPage package.

(c) Pinchuk Igor <i.pinchuk.work@gmail.com>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

$finder = \PhpCsFixer\Finder::create()
    ->files()
    ->name('*.php')
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

return \PhpCsFixer\Config::create()
    ->setUsingCache(true)
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2'                             => true,
        'psr0'                              => true,
        'header_comment'                    => ['header' => $header],
        'trailing_comma_in_multiline_array' => true,
        'cast_spaces'                       => true,
        'no_unused_imports'                 => true,
        'include'                           => true,
        'array_syntax'                      => ['syntax' => 'short'],
    ])
    ->setFinder($finder);