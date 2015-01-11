<?php
namespace Schalla\RBAC\Model\Entity;

use Cake\ORM\Entity;

/**
 * Group Entity.
 */
class Controller extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'controller' => true
    ];

}
