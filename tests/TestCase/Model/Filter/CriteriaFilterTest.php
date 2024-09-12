<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Test\TestCase\Model\Filter;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakeDC\SearchFilter\Model\Filter\CriteriaFilter;
use CakeDC\SearchFilter\Model\Filter\Criterion\InCriterion;
use CakeDC\SearchFilter\Model\Filter\Criterion\StringCriterion;
use PlumSearch\Model\FilterRegistry;

/**
 * CriteriaFilter Test Case
 */
class CriteriaFilterTest extends TestCase
{
    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'plugin.CakeDC/SearchFilter.Articles',
        'plugin.CakeDC/SearchFilter.Authors',
    ];

    /**
     * @var \PlumSearch\Model\FilterRegistry
     */
    protected $registry;

    /**
     * @var \Cake\ORM\Table
     */
    protected $Articles;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Articles = TableRegistry::getTableLocator()->get('Articles');
        $this->registry = new FilterRegistry($this->Articles);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Articles, $this->registry);
        TableRegistry::getTableLocator()->clear();
        parent::tearDown();
    }

    /**
     * Test apply method
     *
     * @return void
     */
    public function testApply(): void
    {
        $criteria = [
            'title' => new StringCriterion('title'),
            'body' => new StringCriterion('body'),
        ];

        $filter = new CriteriaFilter($this->registry, [
            'name' => 'multiple',
            'criteria' => $criteria,
            'filterOptions' => [],
        ]);

        $query = $this->Articles->find();
        $data = [
            'multiple' => [
                'title' => ['condition' => '=', 'value' => ['Test Article']],
                'body' => ['condition' => 'like', 'value' => ['test content']],
            ],
        ];

        $result = $filter->apply($query, $data);

        $this->assertStringContainsString('WHERE', $result->sql());
        $this->assertStringContainsString('title =', $result->sql());
        $this->assertStringContainsString('body LIKE', $result->sql());

        $bindings = $result->getValueBinder()->bindings();
        $this->assertNotEmpty($bindings);
    }

    /**
     * Test apply IN method
     *
     * @return void
     */
    public function testApplyWithInCriterionAndInnerStringCriterion(): void
    {
        $this->Authors = TableRegistry::getTableLocator()->get('Authors');
        $this->Articles->belongsTo('Authors');

        $criteria = [
            'author_id' => new InCriterion('Articles.author_id', $this->Authors, new StringCriterion('name')),
        ];

        $filter = new CriteriaFilter($this->registry, [
            'name' => 'multiple',
            'criteria' => $criteria,
            'filterOptions' => [],
        ]);

        $query = $this->Articles->find();
        $data = [
            'multiple' => [
                'author_id' => [
                    'condition' => 'like',
                    'value' => ['value' => 'John'],
                ],
            ],
        ];

        $result = $filter->apply($query, $data);

        $this->assertMatchesRegularExpression(
            '/Articles\.author_id IN \(SELECT Authors\.id AS "?Authors__id"? FROM authors Authors WHERE name LIKE/',
            $query->sql()
        );

        $bindings = $result->getValueBinder()->bindings();
        $this->assertCount(1, $bindings);
        $this->assertEquals('John%', $bindings[':c0']['value']);
    }

    /**
     * Test apply method with multiple criteria
     *
     * @return void
     */
    public function testApplyWithMultipleCriteria(): void
    {
        $criteria = [
            'title' => new StringCriterion('title'),
            'published' => new StringCriterion('published'),
        ];

        $filter = new CriteriaFilter($this->registry, [
            'name' => 'multiple',
            'criteria' => $criteria,
            'filterOptions' => [],
        ]);

        $query = $this->Articles->find();
        $data = [
            'multiple' => [
                'title' => ['condition' => 'like', 'value' => ['value' => 'Test']],
                'published' => ['condition' => '=', 'value' => ['value' => 'Y']],
            ],
        ];

        $result = $filter->apply($query, $data);

        $this->assertStringContainsString('WHERE', $result->sql());
        $this->assertStringContainsString('title LIKE :c0 AND published = :c1', $result->sql());

        $bindings = $result->getValueBinder()->bindings();
        $this->assertCount(2, $bindings);
        $this->assertEquals('Test%', $bindings[':c0']['value']);
        $this->assertEquals('Y', $bindings[':c1']['value']);
    }
}
