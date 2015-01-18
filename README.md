# RBAC plugin for CakePHP

## WIP - DO NOT USE

## Installation

Add the authorization class in your Auth Object.

$this->loadComponent(‘Auth’, [
    ‘authorize’ => [
        ‘RBAC.Simple’=>[
            ‘Users’=>’Users’
        ]
    ]
];
