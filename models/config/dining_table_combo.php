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
            'name' => [
                'label' => 'lang:admin::lang.label_name',
                'type' => 'text',
                'attributes' => [
                    'readonly' => 'readonly',
                ],
            ],
            'min_capacity' => [
                'label' => 'lang:igniter.reservation::default.dining_tables.label_min_capacity',
                'type' => 'number',
            ],
            'max_capacity' => [
                'label' => 'lang:igniter.reservation::default.dining_tables.label_capacity',
                'type' => 'number',
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
