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


    public function generatePermission()
    {

        $controllerList = $this->_getControllerList();

        $controllerActionList = [];

        foreach ($controllerList as $controller) {

            $controllerInfo = $this->_parseFile($controller);
            $actions = $this->_filterActions($controllerInfo['actions']);

            if (empty($actions)) {
                continue;
            }

            $name = basename($controller);
            $controllerInfo['controller'] = str_replace('Controller.php', '', $name);
            $controllerInfo['actions'] = $actions;

            $controllerActionList[] = $controllerInfo;
        }

        return $controllerActionList;

        //$this->_savePermissions($permissionList);

    }

    private function _getControllerList()
    {

        $folder = new Folder(WWW_ROOT . DS . '..');
        $controllerList = $folder->findRecursive('[A-Za-z]*Controller.php');

        $controllerList = array_filter(
            $controllerList,
            function ($controller) {

                if (substr_count($controller, '/tests/') > 0) {
                    return false;
                }

                if (substr_count($controller, '/vendor/') > 0) {
                    return false;
                }

                return true;
            }
        );

        return $controllerList;

    }

    private function _parseFile($file)
    {

        $tokenList = token_get_all(file_get_contents($file));
        $tokenCount = count($tokenList);

        $functionList = [];
        $namespace = '';

        $nsStart = 0;
        $nsEnd = 0;

        for ($i = 0; $i < $tokenCount; $i++) {

            if ($nsStart > 0 && $nsEnd == 0 && $tokenList[$i] == ';') {
                $nsEnd = $i;
                continue;
            }

            if (!is_array($tokenList[$i])) {
                continue;
            }

            if ($tokenList[$i][0] === 383) {
                $nsStart = $i + 2;
            }

            if ($tokenList[$i][0] == T_FUNCTION) {
                $functionList[] = $tokenList[$i + 2][1];
            }
        }

        if ($nsStart > 0 && $nsEnd > 0) {
            for ($i = $nsStart; $i < $nsEnd; $i++) {
                $namespace .= $tokenList[$i][1];
            }
        }

        return ['namespace' => $namespace, 'actions' => $functionList];
    }

    private function _filterActions($actions)
    {


        $actions = array_filter(
            $actions,
            function ($action) {

                if (in_array($action, $this->invalidActions)) {
                    return false;
                }

                return true;

            }
        );

        return $actions;
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

            $controllerSeg = explode('\\', $controller);
            $controller = array_pop($controllerSeg);
            $namespace = implode('\\', $controllerSeg);

            $controllerCheck = $controllerModel->find()->where(
                [
                    'namespace' => $namespace,
                    'controller' => $controller
                ]
            )->first();

            if ($controllerCheck == null) {
                $controllerCheck = $controllerModel->newEntity();
                $controllerCheck['namespace'] = $namespace;
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
