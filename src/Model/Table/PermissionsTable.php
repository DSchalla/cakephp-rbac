<?php
namespace RBAC\Model\Table;

use Cake\Filesystem\Folder;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;


/**
 * Groups Model
 */
class PermissionsTable extends Table
{

    private $invalidActions = [
        '__construct',
        'initialize',
        'beforeFilter'
    ];

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('rbac_permissions');
        $this->primaryKey('id');
        $this->belongsTo('RBAC.Controllers');
        $this->belongsToMany(
            'RBAC.Groups',
            [
                'joinTable' => 'rbac_groups_permissions'
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator instance
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        return $validator;
    }

    public function saveGeneratePost(array $controllerList)
    {

        $controllerModel = TableRegistry::get('RBAC.Controllers');
        $groupsModel = TableRegistry::get('RBAC.Groups');

        $groups = $groupsModel->find('all')->all();
        $groupEntity = [];

        $joinEntity = new \Cake\ORM\Entity();
        $joinEntity->set('value', 0);

        foreach ($groups as $group) {
            $groupEntity[] = $group;
        }

        foreach ($controllerList as $controller => $actions) {


            $controllerCheck = $controllerModel->find()->where(
                [
                    'controller' => $controller
                ]
            )->first();

            if ($controllerCheck == null) {
                $controllerCheck = $controllerModel->newEntity();
                $controllerCheck['controller'] = $controller;
                $controllerModel->save($controllerCheck);
            }

            foreach ($actions as $action) {

                $entity = $this->newEntity(['action' => $action]);
                $entity->controller = $controllerCheck;
                $entity->groups = $groupEntity;

                foreach ($entity->groups as $group) {

                    $joinEntity = new \Cake\ORM\Entity();
                    $joinEntity->set('value', 0);

                    if ($group->id == 1) {
                        $joinEntity->set('value', 1);
                    } else {
                        $joinEntity->set('value', 0);
                    }

                    $group->set('_joinData', $joinEntity);

                    $group->isNew(true);
                    $group->_joinData->isNew(true);
                }

                $this->save($entity);

            }

        }

        return true;
    }

    public function updatePermissionSet($id, $postData)
    {

        $permissions = $this->find('all')
            ->contain(
                [
                    'Controllers' => [
                        'conditions' => ['Controllers.id' => $id]
                    ],
                    'Groups'
                ]
            )->all();

        foreach ($permissions as $permission) {
            foreach ($permission->groups as $group) {
                $group['_joinData']['value'] = $postData[$permission['action']][$group['title']];
                $this->Groups->RbacGroupsPermissions->save($group['_joinData']);
            }
        }

        return true;
    }

}
