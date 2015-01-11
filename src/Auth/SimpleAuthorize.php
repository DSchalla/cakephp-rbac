<?php
/**
 * Created by Daniel Schalla
 * @author        Daniel Schalla <daniel@schalla.me>
 * @link          https://www.schalla.me
 */

namespace RBAC\Auth;

use Cake\Auth\BaseAuthorize;
use Cake\Network\Request;
use Cake\ORM\TableRegistry;

class SimpleAuthorize extends BaseAuthorize
{

    private $_permissionModel;
    private $_userModel;
    private $_userData;
    private $_request;

    public function authorize($user, Request $request)
    {
        $this->_userData = $user;
        $this->_request = $request;
        $this->_permissionModel = TableRegistry::get('RBAC.Permissions');
        $this->_userModel = TableRegistry::get($this->_config['Users']);
        $this->_userModel->belongsToMany(
            'RBAC.Groups',
            [
                'joinTable' => 'rbac_users_groups'
            ]
        );

        return $this->_checkUser();
    }

    private function _checkUser()
    {

        if (empty($this->_userData['id'])) {
            return false;
        }

        $userData = $this->_userModel->get(
            $this->_userData['id'],
            [
                'contain' => [
                    'Groups',
                    'Groups.Permissions' => [
                        'conditions' => [
                            'action' => $this->_request->params['action']
                        ]
                    ]
                ]
            ]
        );


        $permissionData = $this->_permissionModel
            ->find()
            ->contain(
                [
                    'Controllers',
                    'Groups'
                ]
            )
            ->where(
                [
                    'action' => $this->_request->params['action'],
                    'controller' => get_class($this->_registry->getController())
                ]
            )
            ->first();

        if (!empty($permissionData)) {
            return $this->_checkGroups($permissionData, $userData);
        }

        return true;
    }

    private function _checkGroups($permission, $user)
    {

        $validGroups = [];
        $valid = false;

        foreach ($permission->groups as $group) {
            if ($group->_joinData['value']) {
                $validGroups[] = $group['id'];
            }
        }

        foreach ($user->groups as $group) {
            if (in_array($group['id'], $validGroups)) {
                $valid = true;
                break;
            }
        }

        if ($valid) {
            return true;
        } else {
            return false;
        }

    }

}