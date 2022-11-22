<?php

$header = <<<EOF
  What php team is that is 'one thing, a team, work together'
EOF;

$finder = PhpCsFixer\Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('vendor')
    ->in(__DIR__)
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

// 官方配置规则文档 https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/index.rst

$rules = [
    '@PSR2'                               => true,
    '@PSR12'                              => true,
    'strict_param'                        => true,
    '@Symfony'                            => true,             // 开启 symfony 配置，在官方文档中需要看 @symfony部分

    // Array Notation
    'header_comment'                      => ['header' => ''], // 文件头注释
    'array_syntax'                        => ['syntax' => 'short'],
    'no_whitespace_before_comma_in_array' => ['after_heredoc' => true], // 数组中逗号前面没有空格
    'trim_array_spaces'                   => true,                      // 去掉数组中多余的空格

    // Basic
    'braces' => [
        'allow_single_line_anonymous_class_with_empty_body' => true,
        'allow_single_line_closure'                         => true,
        'position_after_functions_and_oop_constructs'       => 'same',
    ],

    // Comment
    'no_empty_comment' => true, // 去掉空注释

    // Import
    'ordered_imports'                     => true,                      // 按顺序use导入
    'no_unused_imports'                   => true,                      // 删除没用到的use

    'no_useless_else'                            => true, // 删除没有使用的else节点
    'no_useless_return'                          => true, // 删除没有使用的return语句
    'self_accessor'                              => true, // 在当前类中使用 self 代替类名
    'php_unit_construct'                         => true,
    'single_quote'                               => true,                 // 简单字符串应该使用单引号代替双引号
    'no_singleline_whitespace_before_semicolons' => true,                 // 禁止只有单行空格和分号的写法
    'no_empty_statement'                         => true,                 // 多余的分号
    'no_whitespace_in_blank_line'                => true,                 // 删除空行中的空格
    'standardize_not_equals'                     => true,                 // 使用 <> 代替 !=
    'combine_consecutive_unsets'                 => true,                 // 当多个 unset 使用的时候，合并处理
    'concat_space'                               => ['spacing' => 'one'], // .拼接必须有空格分割
    'array_indentation'                          => true,                 // 数组的每个元素必须缩进一次
    'blank_line_before_statement'                => [
        'statements' => [
            'break',
            'continue',
            'declare',
            'return',
            'throw',
            'try',
        ],
    ], // 空行换行必须在任何已配置的语句之前
    'binary_operator_spaces'                     => [
        'default' => 'align_single_space',
    ], //等号对齐、数字箭头符号对齐

    // PHPDoc
    'align_multiline_comment'                    => ['comment_type' => 'phpdocs_only'],
    'no_blank_lines_after_phpdoc'                => true,
    'phpdoc_line_span'                           => true,
    'no_empty_phpdoc'                            => true,
    'phpdoc_separation'                          => false, // 不同注释部分按照单空行隔开
    'phpdoc_single_line_var_spacing'             => true,
    'phpdoc_indent'                              => true,
    'no_superfluous_phpdoc_tags'                 => false, // 删除没有提供有效信息的@param和@return注解
    'phpdoc_single_line_var_spacing'             => true,
    'phpdoc_summary'                             => true,
    'phpdoc_align'                               => [
        'align' => 'vertical',
        'tags'  => [
            'param', 'throws', 'type', 'var', 'property',
        ],
    ],
    'phpdoc_var_annotation_correct_order' => true,

    // Semicolon
    'multiline_whitespace_before_semicolons'     => true,
    'no_empty_statement'                         => true,
    'no_singleline_whitespace_before_semicolons' => true,
    'space_after_semicolon'                      => ['remove_in_empty_for_expressions' => true],

    'lowercase_cast'                     => true, // 类型强制小写
    'constant_case'                      => true, // 常量为小写
    'lowercase_static_reference'         => true, // 静态调用为小写
    'no_blank_lines_after_class_opening' => true,
];

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setFinder($finder);
