<?php
declare(strict_types=1);

/**
 * Copyright 2024 - 2024, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2018, Cake Development Corporation (https://www.cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\SearchFilter\Test\App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;

/**
 * Articles Table
 *
 * @property \CakeDC\SearchFilter\Test\App\Model\Table\AuthorsTable|\Cake\ORM\Association\BelongsTo $Authors
 * @method \PlumSearch\Model\FilterRegistry filters()
 * @method \Cake\ORM\Table addFilter(string $name, array $options = [])
 * @method \Cake\ORM\Table removeFilter(string $name)
 */
class ArticlesTable extends Table
{
    /**
     * Initialize method
     *
     * @param  array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        $this->setTable('articles');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->addBehavior('PlumSearch.Filterable');
        $this->addFilter('title', ['className' => 'Like']);
        $this->addFilter('author_id', ['className' => 'Value']);

        $this->belongsTo('Authors')->setForeignKey('author_id');
    }

    /**
     * Authors search finder
     *
     * @param  Query $query query object instance
     * @return $this
     */
    public function findWithAuthors(Query $query)
    {
        return $query->matching('Authors');
    }
}
