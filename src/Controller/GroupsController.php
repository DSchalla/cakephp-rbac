<?php
namespace RBAC\Controller;

/**
 * Groups Controller
 *
 * @property \App\Model\Table\GroupsTable $Groups
 */
class GroupsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('groups', $this->paginate($this->Groups));
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
    public function add()
    {
        $group = $this->Groups->newEntity($this->request->data);
        if ($this->request->is('post')) {
            if ($this->Groups->save($group)) {
                $this->Flash->success('The group has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The group could not be saved. Please, try again.');
            }
        }
        $this->set(compact('group'));
    }

    /**
     * Edit method
     *
     * @param string|null $id group id
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException
     */
    public function edit($id = null)
    {
        $group = $this->Groups->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $group = $this->Groups->patchEntity($group, $this->request->data);
            if ($this->Groups->save($group)) {
                $this->Flash->success('The group has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The group could not be saved. Please, try again.');
            }
        }
        $this->set(compact('group'));
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
        $group = $this->Groups->get($id);
        $this->request->allowMethod(['post', 'delete']);
        if ($this->Groups->delete($group)) {
            $this->Flash->success('The group has been deleted.');
        } else {
            $this->Flash->error('The group could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'index']);
    }

}
