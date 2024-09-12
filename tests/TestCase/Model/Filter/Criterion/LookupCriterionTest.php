<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Test\TestCase\Model\Filter\Criterion;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakeDC\SearchFilter\Filter\AbstractFilter;
use CakeDC\SearchFilter\Model\Filter\Criterion\LookupCriterion;
use CakeDC\SearchFilter\Model\Filter\Criterion\StringCriterion;

class LookupCriterionTest extends TestCase
{
    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'plugin.CakeDC/SearchFilter.Articles',
        'plugin.CakeDC/SearchFilter.Authors',
    ];

    /**
     * @var \Cake\ORM\Table
     */
    protected $Articles;

    /**
     * @var \Cake\ORM\Table
     */
    protected $Authors;

    /**
     * @var \CakeDC\SearchFilter\Model\Filter\Criterion\LookupCriterion
     */
    protected $lookupCriterion;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->Articles = TableRegistry::getTableLocator()->get('Articles');
        $this->Authors = TableRegistry::getTableLocator()->get('Authors');
        $this->Articles->belongsTo('Authors');

        $innerCriterion = new StringCriterion('name');
        $this->lookupCriterion = new LookupCriterion('Articles.author_id', $this->Authors, $innerCriterion);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Articles, $this->Authors, $this->lookupCriterion);
        TableRegistry::getTableLocator()->clear();
        parent::tearDown();
    }

    /**
     * Test constructor
     *
     * @return void
     */
    public function testConstructor(): void
    {
        $this->assertInstanceOf(LookupCriterion::class, $this->lookupCriterion);
    }

    /**
     * Test isApplicable method
     *
     * @return void
     */
    public function testIsApplicable(): void
    {
        $this->assertTrue($this->lookupCriterion->isApplicable(['id' => 1], AbstractFilter::COND_EQ));
        $this->assertTrue($this->lookupCriterion->isApplicable(['value' => 'John'], AbstractFilter::COND_LIKE));
        $this->assertFalse($this->lookupCriterion->isApplicable([], AbstractFilter::COND_EQ));
        $this->assertFalse($this->lookupCriterion->isApplicable('', AbstractFilter::COND_LIKE));
    }

    /**
     * Test buildFilter method with 'like' condition
     *
     * @return void
     */
    public function testBuildLikeFilter(): void
    {
        $condition = AbstractFilter::COND_LIKE;
        $values = ['value' => 'John'];
        $criteria = ['Articles.author_id' => 'John'];
        $options = [];

        $result = $this->lookupCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $query = $this->Articles->find();
        $modifiedQuery = $query->where($result);

        $this->assertMatchesRegularExpression(
            '/Articles\.author_id IN \(SELECT Authors\.id AS "?Authors__id"? FROM authors Authors WHERE name LIKE/',
            $query->sql()
        );
        $bindings = $modifiedQuery->getValueBinder()->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertEquals('John%', $bindings[':c0']['value']);
    }

    /**
     * Test buildFilter method with '=' condition
     *
     * @return void
     */
    public function testBuildEqualFilter(): void
    {
        $condition = AbstractFilter::COND_EQ;
        $values = ['id' => 1];
        $criteria = ['Articles.author_id' => 1];
        $options = [];

        $result = $this->lookupCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $query = $this->Articles->find();
        $modifiedQuery = $query->where($result);

        $this->assertStringContainsString('Articles.author_id =', $modifiedQuery->sql());

        $bindings = $modifiedQuery->getValueBinder()->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertEquals(1, $bindings[':c0']['value']);
    }

    /**
     * Test buildQueryByCondition method with case-sensitive LIKE
     *
     * @return void
     */
    public function testBuildQueryByConditionLikeCaseSensitive(): void
    {
        $condition = AbstractFilter::COND_LIKE;
        $values = ['value' => 'John'];
        $criteria = ['Articles.author_id' => ['condition' => $condition, 'value' => $values]];
        $options = [];

        $result = $this->lookupCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $query = $this->Articles->find();
        $modifiedQuery = $query->where($result);

        $this->assertMatchesRegularExpression(
            '/Articles\.author_id IN \(SELECT Authors\.id AS "?Authors__id"? FROM authors Authors WHERE name LIKE/',
            $query->sql()
        );

        $bindings = $modifiedQuery->getValueBinder()->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertEquals('John%', $bindings[':c0']['value']);
    }

    /**
     * Test buildQueryByCondition method with case-insensitive LIKE
     *
     * @return void
     */
    public function testBuildQueryByConditionLikeCaseInsensitive(): void
    {
        $condition = AbstractFilter::COND_LIKE;
        $values = ['value' => 'John'];
        $criteria = ['Articles.author_id' => ['condition' => $condition, 'value' => $values]];
        $options = ['caseInsensitive' => true];

        $result = $this->lookupCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $query = $this->Articles->find();
        $modifiedQuery = $query->where($result);

        $this->assertMatchesRegularExpression(
            '/Articles\.author_id IN \(SELECT Authors\.id AS "?Authors__id"? FROM authors Authors WHERE name ILIKE/',
            $query->sql()
        );

        $bindings = $modifiedQuery->getValueBinder()->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertEquals('John%', $bindings[':c0']['value']);
    }
}
