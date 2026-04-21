<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Filtertags Model
 *
 * @property \App\Model\Table\ProductsTable&\Cake\ORM\Association\BelongsToMany $Products
 *
 * @method \App\Model\Entity\Filtertag newEmptyEntity()
 * @method \App\Model\Entity\Filtertag newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Filtertag> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Filtertag get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Filtertag findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Filtertag patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Filtertag> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Filtertag|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Filtertag saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Filtertag>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Filtertag>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Filtertag>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Filtertag> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Filtertag>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Filtertag>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Filtertag>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Filtertag> deleteManyOrFail(iterable $entities, array $options = [])
 */
class FiltertagsTable extends Table
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

        $this->setTable('filtertags');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Products', [
            'foreignKey' => 'filtertag_id',
            'targetForeignKey' => 'product_id',
            'joinTable' => 'products_filtertags',
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
            ->scalar('name')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        return $validator;
    }
}
