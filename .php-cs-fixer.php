<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['bootstrap', 'storage', 'vendor'])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'blank_line_after_opening_tag' => true,
        'cast_spaces' => true,
        'function_typehint_space' => true,
        'heredoc_to_nowdoc' => true,
        'include' => true,
        'lowercase_cast' => true,
        'magic_constant_casing' => true,
        'modernize_types_casting' => true,
        'native_function_casing' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_extra_blank_lines' => [
            'tokens' => ['case', 'continue', 'curly_brace_block', 'default', 'extra', 'parenthesis_brace_block', 'square_brace_block', 'switch', 'throw', 'use', 'use_trait'],
        ],
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'no_leading_import_slash' => true,
        'no_leading_namespace_whitespace' => true,
        'no_mixed_echo_print' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_short_bool_cast' => true,
        'no_superfluous_phpdoc_tags' => true,
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        'normalize_index_brace' => true,
        'not_operator_with_successor_space' => true,
        'object_operator_without_whitespace' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'short_scalar_cast' => true,
        'single_blank_line_at_eof' => true,
        'single_blank_line_before_namespace' => true,
        'single_quote' => true,
        'standardize_not_equals' => true,
        'trailing_comma_in_multiline' => true,
        'trim_array_spaces' => true,
        'unary_operator_spaces' => true,
        'visibility_required' => true,
        'whitespace_after_comma_in_array' => true,
    ]);

return $config;
