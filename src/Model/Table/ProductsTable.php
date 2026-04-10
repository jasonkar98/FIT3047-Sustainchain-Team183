<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ProductsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('products');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        // Auto-manage created and modified timestamps
        $this->addBehavior('Timestamp');

        // A product BELONGS TO one seller (who is a user)
        $this->belongsTo('Sellers', [
            'className'  => 'Users',
            'foreignKey' => 'seller_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('name', 'A product name is required')
            ->notEmptyString('description', 'A description is required')
            ->greaterThan('price', 0, 'Price must be greater than 0')
            ->notEmptyString('category', 'A category is required');

        return $validator;
    }
}