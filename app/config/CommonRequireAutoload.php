<?php

$config = [
    'site' => [
        'comments' => [
            'creation_settings' => [
                'text_sizes' => [
                    'username' => [
                        'min' => 1,
                        'max' => DbHelper::obj()->getCharacterMaximumLength(CommentModel::getTableName(), 'username')
                    ],
                    'mail' => [
                        'min' => 5,
                        'max' => DbHelper::obj()->getCharacterMaximumLength(CommentModel::getTableName(), 'email')
                    ],
                    'text' => [
                        'min' => 1,
                        'max' => DbHelper::obj()->getCharacterMaximumLength(CommentModel::getTableName(), 'text')
                    ]
                ]
            ]
        ]
    ]
];

return $config;