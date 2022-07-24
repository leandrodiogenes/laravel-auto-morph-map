<?php

use LeandroDiogenes\AutoMorphMap\Constants\CaseTypes;
use LeandroDiogenes\AutoMorphMap\Constants\NamingSchemes;

return [

    /**
     * The naming scheme to use when determining the model's morph type
     * base value. Defaults to singular table name.
     */

    'naming' => NamingSchemes::SINGULAR_TABLE_NAME,

    /**
     * The case type to use when aliasing a model. Defaults to use snake case.
     */

    'case' => CaseTypes::SNAKE_CASE,

    /**
     * If you wish, you can override the naming and conversion altogether
     * using a closure.
     */

    // 'conversion' => function (string $model) {
    //     return snake_case(basename($model));
    // },

];
