# RBAC plugin for CakePHP

## Installation

Add the authorization class in your Auth Object.

$this->loadComponent(‘Auth’, [
    ‘authorize’ => [
        ‘RBAC.Simple’=>[
            ‘Users’=>’Users’
        ]
    ]
];
