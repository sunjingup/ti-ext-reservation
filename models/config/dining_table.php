<?php

return [
    'form' => [
        'fields' => [
            'id' => [
                'type' => 'hidden',
            ],
            'priority' => [
                'type' => 'hidden',
            ],
            'is_selected' => [
                'label' => 'lang:admin::lang.text_select',
                'type' => 'checkbox',
                'options' => [],
            ],
            'name' => [
                'label' => 'lang:admin::lang.label_name',
                'type' => 'text',
            ],
            'min_capacity' => [
                'label' => 'lang:igniter.reservation::default.dining_tables.label_min_capacity',
                'type' => 'number',
            ],
            'max_capacity' => [
                'label' => 'lang:igniter.reservation::default.dining_tables.label_capacity',
                'type' => 'number',
            ],
            'extra_capacity' => [
                'label' => 'lang:igniter.reservation::default.dining_tables.label_extra_capacity',
                'type' => 'number',
                'comment' => 'lang:igniter.reservation::default.dining_tables.help_extra_capacity',
            ],
            'section_name' => [
                'label' => 'lang:igniter.reservation::default.dining_tables.column_section',
                'type' => 'text',
                'attributes' => [
                    'readonly' => 'readonly',
                ],
            ],
            'is_enabled' => [
                'label' => 'lang:igniter.reservation::default.dining_tables.label_is_enabled',
                'type' => 'checkbox',
                'options' => [],
                'default' => 1,
            ],
        ],
    ],
];
