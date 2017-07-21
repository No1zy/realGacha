<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Gachas Model
 *
 * @property \App\Model\Table\GachasTable|\Cake\ORM\Association\BelongsTo $Gachas
 * @property \App\Model\Table\EventsTable|\Cake\ORM\Association\HasMany $Events
 * @property \App\Model\Table\GachasTable|\Cake\ORM\Association\HasMany $Gachas
 *
 * @method \App\Model\Entity\Gacha get($primaryKey, $options = [])
 * @method \App\Model\Entity\Gacha newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Gacha[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Gacha|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Gacha patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Gacha[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Gacha findOrCreate($search, callable $callback = null, $options = [])
 */
class GachasTable extends Table
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

        $this->setTable('gachas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Gachas', [
            'foreignKey' => 'gacha_id'
        ]);
        $this->hasMany('Events', [
            'foreignKey' => 'gacha_id'
        ]);
        $this->hasMany('Gachas', [
            'foreignKey' => 'gacha_id'
        ]);
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
            ->allowEmpty('area');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['gacha_id'], 'Gachas'));

        return $rules;
    }
}
