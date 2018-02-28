<?php
return [
    'settings' => [
        'displayErrorDetails' => true,
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],
        'database' => [
            'dsn' => 'sqlite:' . __DIR__ . '/../db/vm_db.sq3'
        ]
    ]
];
