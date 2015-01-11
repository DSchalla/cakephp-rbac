<?php
namespace RBAC\Model\Entity;

use Cake\ORM\Entity;

/**
 * Group Entity.
 */
class Permission extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'action' => true,
    ];

}
