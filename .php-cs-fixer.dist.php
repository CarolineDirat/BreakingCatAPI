<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor/')
    ->exclude('templates/')
    ->exclude('bin/')
    ->exclude('config/')
    ->exclude('migrations/')
    ->exclude('translations/')
    ->exclude('var/')
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        '@PSR12' => true,
        '@PSR1' => true,
        '@PhpCsFixer' => true,
        '@DoctrineAnnotation' => true,
        'concat_space' => false,
        'array_syntax' => ['syntax' => 'short'],
        'no_superfluous_phpdoc_tags' => false,
        'php_unit_test_class_requires_covers' => false,
        'php_unit_internal_class' => false,
        //'hash_to_slash_comment' => false,
        //'single_line_comment_style' => false,
    ])
    ->setFinder($finder)
;
