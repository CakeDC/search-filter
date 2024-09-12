<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Test\App\Controller;

use CakeDC\SearchFilter\Manager;

/**
 * Articles Controller
 *
 * @property \CakeDC\SearchFilter\Test\App\Model\Table\ArticlesTable $Articles
 * @property FilterComponent $Filter
 */
class ArticlesController extends AppController
{
    /**
     * initialize callback
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $author = $this->Articles->Authors;
        $this->loadComponent('PlumSearch.Filter', [
            'formName' => 'Article',
            'parameters' => [
                ['name' => 'title', 'className' => 'Input'],
                [
                    'name' => 'author_id',
                    'className' => 'Select',
                    'finder' => $author->find('list'),
                ],
            ],
        ]);
        $this->viewBuilder()->addHelpers([
            'PlumSearch.Search',
        ]);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $query = $this->Articles->find();
        $manager = new Manager($this->request);
        $collection = $manager->newCollection();

        $collection->add('search', $manager->filters()
            ->new('string')
            ->setConditions(new \stdClass())
            ->setLabel('Search...'));

        $collection->add('name', $manager->filters()
            ->new('string')
            ->setLabel('Name')
            ->setCriterion(
                $manager->criterion()->or([
                    $manager->buildCriterion('title', 'string', $this->Articles),
                    $manager->buildCriterion('body', 'string', $this->Articles),
                ])
            ));

        $collection->add('created', $manager->filters()
            ->new('datetime')
            ->setLabel('Created')
            ->setCriterion($manager->buildCriterion('created', 'datetime', $this->Articles)));

        $manager->appendFromSchema($collection, $this->Articles);

        $viewFields = $collection->getViewConfig();
        $this->set('viewFields', $viewFields);
        if (!empty($this->getRequest()->getQuery()) && !empty($this->getRequest()->getQuery('f'))) {
            $search = $manager->formatSearchData();
            $this->set('values', $search);

            $this->Articles->addFilter('multiple', [
                'className' => 'CakeDC/SearchFilter.Criteria',
                'criteria' => $collection->getCriteria(),
            ]);

            $filters = $manager->formatFinders($search);
            $query = $query->find('filters', $filters);
        }

        $query = $this->Filter->prg($query);

        $this->set('articles', $this->paginate($query));
    }

    /**
     * Search method
     *
     * @return void
     */
    public function search()
    {
        $query = $this->Filter->prg($this->Articles->find('withAuthors'));
        $this->set('articles', $this->paginate($query));
    }
}
