<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductsFiltertags Model
 *
 * @property \App\Model\Table\ProductsTable&\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\FiltertagsTable&\Cake\ORM\Association\BelongsTo $Filtertags
 *
 * @method \App\Model\Entity\ProductsFiltertag newEmptyEntity()
 * @method \App\Model\Entity\ProductsFiltertag newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\ProductsFiltertag> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductsFiltertag get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\ProductsFiltertag findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\ProductsFiltertag patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\ProductsFiltertag> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductsFiltertag|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\ProductsFiltertag saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\ProductsFiltertag>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductsFiltertag>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProductsFiltertag>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductsFiltertag> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProductsFiltertag>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductsFiltertag>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProductsFiltertag>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductsFiltertag> deleteManyOrFail(iterable $entities, array $options = [])
 */
class ProductsFiltertagsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('products_filtertags');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Filtertags', [
            'foreignKey' => 'filtertag_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('product_id')
            ->notEmptyString('product_id');

        $validator
            ->integer('filtertag_id')
            ->notEmptyString('filtertag_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['product_id'], 'Products'), ['errorField' => 'product_id']);
        $rules->add($rules->existsIn(['filtertag_id'], 'Filtertags'), ['errorField' => 'filtertag_id']);

        return $rules;
    }
}
