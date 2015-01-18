<?php
use Cake\Core\Configure;

$config = [
    'User'=> [
        'Model' => 'User.Users'
    ],
    'Group' => [
        'Model' => 'RBAC.Groups'
    ]
];

Configure::write('RBAC', $config);
