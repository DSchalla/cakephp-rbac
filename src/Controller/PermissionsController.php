<?php
/**
 * Created by Daniel Schalla
 * @author        Daniel Schalla <daniel@schalla.me>
 * @link          https://www.schalla.me
 */

namespace Schalla\RBAC\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

class PermissionsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $controllers = TableRegistry::get('Schalla/RBAC.Controllers');

        $this->set('controllers', $this->paginate($controllers));
    }

    /**
     * View method
     *
     * @param string|null $id permission id
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException
     */
    public function view($id = null)
    {

        $controllers = TableRegistry::get('Schalla/RBAC.Controllers');
        $controller = $controllers->get($id);

        if (empty($controller)) {
            throw new NotFoundException;
        }

        $permissions = $this->Permissions->find('all', ['contain' => 'Controllers'])->where(['controller_id' => $id]);


        $this->set('controller', $controller);
        $this->set('permissions', $this->paginate($permissions));
    }

    /**
     * Generate Method
     *
     * @return void
     */

    public function generate()
    {

        $controllerActionList = $this->Permissions->generatePermission();

        if ($this->request->is('post')) {
            $controllerList = array_filter($this->request->data);
            $this->Permissions->saveGeneratePost($controllerList);
            return $this->redirect(['action' => 'index']);
        }

        $this->set('controllerActionList', $controllerActionList);
    }


    /**
     * Edit method
     *
     * @param string|null $id permission id
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException
     */
    public function edit($id = null)
    {

        $controllersTable = TableRegistry::get('Schalla/RBAC.Controllers');
        $groupsTable = TableRegistry::get('Schalla/RBAC.Groups');

        $controller = $controllersTable->get($id);

        if (empty($controller)) {
            throw new NotFoundException;
        }

        $groups = $groupsTable->find();
        $permissions = $this->Permissions->find('all', ['contain' => ['Groups']])->where(['controller_id' => $id])->all(
        );

        if ($this->request->is(['patch', 'post', 'put'])) {
            if ($this->Permissions->updatePermissionSet($id, $this->request->data)) {
                $this->Flash->success('The permission has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The permission could not be saved. Please, try again.');
            }
        }


        $this->set('controller', $controller);
        $this->set('groups', $groups);
        $this->set('permissions', $permissions);
    }

    /**
     * Delete method
     *
     * @param string|null $id permission id
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException
     */
    public function delete($id = null)
    {

        $permission = $this->permissions->get($id);
        $this->request->allowMethod(['post', 'delete']);
        if ($this->permissions->delete($permission)) {
            $this->Flash->success('The permission has been deleted.');
        } else {
            $this->Flash->error('The permission could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'index']);
    }


    public function getController()
    {
        $this->Permissions->generatePermission();
    }
} 