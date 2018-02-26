<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BlockedIps Model
 *
 * @method \App\Model\Entity\BlockedIp get($primaryKey, $options = [])
 * @method \App\Model\Entity\BlockedIp newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\BlockedIp[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BlockedIp|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BlockedIp patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BlockedIp[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\BlockedIp findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BlockedIpsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('blocked_ips');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('ip_address')
            ->maxLength('ip_address', 255)
            ->requirePresence('ip_address', 'create')
            ->notEmpty('ip_address');

        $validator
            ->scalar('reason')
            ->maxLength('reason', 255)
            ->requirePresence('reason', 'create')
            ->notEmpty('reason');

        return $validator;
    }
}
