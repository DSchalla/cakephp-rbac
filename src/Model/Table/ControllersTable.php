<?php
namespace RBAC\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Groups Model
 */
class ControllersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('rbac_controllers');
        $this->displayField('controller');
        $this->primaryKey('id');
        $this->hasMany('RBAC.Permissions');
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
            ->allowEmpty('id', 'create')
            ->requirePresence('controller', 'create')
            ->notEmpty('controller');

        return $validator;
    }
}
