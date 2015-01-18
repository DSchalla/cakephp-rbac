<?php
namespace RBAC\Controller;

use Cake\Core\Configure;
use Cake\ORM\Exception\MissingEntityException;
use Cake\ORM\TableRegistry;

/**
 * Groups Controller
 *
 * @property \App\Model\Table\GroupsTable $Groups
 */
class MembersController extends AppController
{
    private $_config;

    public function initialize()
    {
        $this->theme='Theme/CsphereBackend';
        $this->_config=Configure::read('RBAC');
        $this->loadModel('RBAC.Groups');
        $this->loadModel($this->_config['User']['Model']);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index($id = null)
    {
        $group = $this->Groups->get($id, ['contain'=>'Users']);

        $users=$this->Users->find()->matching('Groups', function ($q) use ($id) {
            return $q->where(['Groups.id' => $id]);
        });

        $this->set('group', $group);
        $this->set('users', $this->Paginate($users));
    }

    public function view($id = null)
    {
        $group = $this->Groups->get($id, [
                'contain' => ['Users']
            ]);
        $this->set('group', $group);
        $this->set('_serialize', ['group']);
    }

    /**
     * Add method
     *
     * @return void
     */
    public function add($id = null)
    {
        $group = $this->Groups->get($id, ['contain'=>'Users']);
        $users = $this->Users->find('list');

        if (!$group->is_assignable) {
            $this->Flash->error('The group cannot be assigned.');
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post','put'])) {

            $success=true;

            foreach ($this->request->data['users'] as $user) {
                $entity=$this->Users->RbacUsersGroups->newEntity();
                $entity['group_id']=$id;
                $entity['user_id']=$user;

                if (!$this->Users->RbacUsersGroups->save($entity)) {
                    $success=false;
                }
            }

            if ($success) {
                $this->Flash->success('All new members have been saved.');
                return $this->redirect(['action' => 'index', $id]);
            } else {
                $this->Flash->error('Not able to create all memberships. Please, try again.');
            }
        }
        $this->set(compact('group'));
        $this->set(compact('users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id group id
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException
     */
    public function delete($id = null)
    {
        $relationTable=TableRegistry::get('rbac_users_groups');
        $relation = $relationTable->get($id);
        $this->request->allowMethod(['post', 'delete']);
        if ($relationTable->delete($relation)) {
            $this->Flash->success('The membership has been deleted.');
        } else {
            $this->Flash->error('The membership could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'index', $relation->group_id]);
    }
}
