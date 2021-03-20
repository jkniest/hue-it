<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/spec')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return PhpCsFixer\Config::create()
    ->setRules([
        'align_multiline_comment'                       => ['comment_type' => 'phpdocs_like'],
        'array_indentation'                             => true,
        'array_syntax'                                  => ['syntax' => 'short'],
        'binary_operator_spaces'                        => ['align_double_arrow' => true],
        'blank_line_after_namespace'                    => true,
        'blank_line_after_opening_tag'                  => true,
        'blank_line_before_statement'                   => true,
        'braces'                                        => true,
        'cast_spaces'                                   => true,
        'class_attributes_separation'                   => true,
        'class_definition'                              => ['single_line' => true],
        'combine_consecutive_issets'                    => true,
        'combine_consecutive_unsets'                    => true,
        'comment_to_phpdoc'                             => true,
        'compact_nullable_typehint'                     => true,
        'concat_space'                                  => true,
        'declare_strict_types'                          => true,
        'dir_constant'                                  => true,
        'elseif'                                        => true,
        'encoding'                                      => true,
        'escape_implicit_backslashes'                   => true,
        'explicit_indirect_variable'                    => true,
        'explicit_string_variable'                      => true,
        'full_opening_tag'                              => true,
        'fully_qualified_strict_types'                  => true,
        'function_declaration'                          => true,
        'function_to_constant'                          => true,
        'function_typehint_space'                       => true,
        'implode_call'                                  => true,
        'include'                                       => true,
        'increment_style'                               => true,
        'indentation_type'                              => true,
        'is_null'                                       => true,
        'line_ending'                                   => true,
        'list_syntax'                                   => ['syntax' => 'short'],
        'logical_operators'                             => true,
        'lowercase_cast'                                => true,
        'lowercase_constants'                           => true,
        'lowercase_keywords'                            => true,
        'lowercase_static_reference'                    => true,
        'magic_constant_casing'                         => true,
        'magic_method_casing'                           => true,
        'mb_str_functions'                              => true,
        'method_argument_space'                         => ['ensure_fully_multiline' => true, 'on_multiline' => 'ensure_fully_multiline'],
        'method_chaining_indentation'                   => true,
        'modernize_types_casting'                       => true,
        'multiline_comment_opening_closing'             => true,
        'multiline_whitespace_before_semicolons'        => true,
        'native_function_casing'                        => true,
        'no_alias_functions'                            => true,
        'no_alternative_syntax'                         => true,
        'no_binary_string'                              => true,
        'no_blank_lines_after_class_opening'            => true,
        'no_blank_lines_after_phpdoc'                   => true,
        'no_break_comment'                              => true,
        'no_closing_tag'                                => true,
        'no_empty_comment'                              => true,
        'no_empty_phpdoc'                               => true,
        'no_empty_statement'                            => true,
        'no_extra_blank_lines'                          => true,
        'no_leading_import_slash'                       => true,
        'no_leading_namespace_whitespace'               => true,
        'no_mixed_echo_print'                           => true,
        'no_multiline_whitespace_around_double_arrow'   => true,
        'no_null_property_initialization'               => true,
        'no_singleline_whitespace_before_semicolons'    => true,
        'no_spaces_after_function_name'                 => true,
        'no_spaces_around_offset'                       => true,
        'no_spaces_inside_parenthesis'                  => true,
        'no_superfluous_elseif'                         => true,
        'no_superfluous_phpdoc_tags'                    => true,
        'no_trailing_comma_in_list_call'                => true,
        'no_trailing_comma_in_singleline_array'         => true,
        'no_trailing_whitespace'                        => true,
        'no_trailing_whitespace_in_comment'             => true,
        'no_unneeded_control_parentheses'               => true,
        'no_unneeded_curly_braces'                      => true,
        'no_unneeded_final_method'                      => true,
        'no_unset_cast'                                 => true,
        'no_unused_imports'                             => true,
        'no_useless_else'                               => true,
        'no_useless_return'                             => true,
        'no_whitespace_before_comma_in_array'           => true,
        'no_whitespace_in_blank_line'                   => true,
        'normalize_index_brace'                         => true,
        'object_operator_without_whitespace'            => true,
        'ordered_imports'                               => ['sort_algorithm' => 'length'],
        'php_unit_construct'                            => true,
        'php_unit_dedicate_assert'                      => true,
        'php_unit_method_casing'                        => ['case' => 'snake_case'],
        'php_unit_set_up_tear_down_visibility'          => true,
        'php_unit_strict'                               => true,
        'php_unit_test_annotation'                      => ['style' => 'annotation'],
        'php_unit_test_case_static_method_calls'        => ['call_type' => 'this'],
        'phpdoc_add_missing_param_annotation'           => true,
        'phpdoc_align'                                  => true,
        'phpdoc_annotation_without_dot'                 => true,
        'phpdoc_indent'                                 => true,
        'phpdoc_inline_tag'                             => true,
        'phpdoc_no_alias_tag'                           => true,
        'phpdoc_no_empty_return'                        => true,
        'phpdoc_no_package'                             => true,
        'phpdoc_no_useless_inheritdoc'                  => true,
        'phpdoc_order'                                  => true,
        'phpdoc_return_self_reference'                  => true,
        'phpdoc_scalar'                                 => true,
        'phpdoc_separation'                             => true,
        'phpdoc_single_line_var_spacing'                => true,
        'phpdoc_summary'                                => true,
        'phpdoc_to_comment'                             => true,
        'phpdoc_trim'                                   => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types'                                  => true,
        'phpdoc_types_order'                            => true,
        'phpdoc_var_annotation_correct_order'           => true,
        'phpdoc_var_without_name'                       => true,
        'protected_to_private'                          => true,
        'random_api_migration'                          => true,
        'return_assignment'                             => true,
        'return_type_declaration'                       => true,
        'self_accessor'                                 => true,
        'semicolon_after_instruction'                   => true,
        'set_type_to_cast'                              => true,
        'short_scalar_cast'                             => true,
        'simplified_null_return'                        => true,
        'single_blank_line_at_eof'                      => true,
        'single_blank_line_before_namespace'            => true,
        'single_class_element_per_statement'            => true,
        'single_import_per_statement'                   => true,
        'single_line_after_imports'                     => true,
        'single_line_comment_style'                     => true,
        'single_quote'                                  => true,
        'space_after_semicolon'                         => true,
        'standardize_increment'                         => true,
        'standardize_not_equals'                        => true,
        'static_lambda'                                 => true,
        'strict_comparison'                             => true,
        'strict_param'                                  => true,
        'switch_case_semicolon_to_colon'                => true,
        'switch_case_space'                             => true,
        'ternary_operator_spaces'                       => true,
        'ternary_to_null_coalescing'                    => true,
        'trailing_comma_in_multiline_array'             => true,
        'trim_array_spaces'                             => true,
        'unary_operator_spaces'                         => true,
        'visibility_required'                           => true,
        'void_return'                                   => true,
        'whitespace_after_comma_in_array'               => true,
        'yoda_style'                                    => true,
        'simple_to_complex_string_variable'             => true,
        'ordered_interfaces'                            => ['order' => 'length'],
        'native_function_type_declaration_casing'       => true,
        'php_unit_dedicate_assert_internal_type'        => true,
    ])
    ->setFinder($finder);
