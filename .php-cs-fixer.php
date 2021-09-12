<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = jkniest\Linting\styles($finder);

$rules = array_merge($config->getRules(), [
    'trailing_comma_in_multiline' => ['elements' => ['arrays', 'arguments']] // Until PHP 7.4 support is dropped
]);

return $config->setRules($rules);
