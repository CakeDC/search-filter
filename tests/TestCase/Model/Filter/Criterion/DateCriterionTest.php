<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Test\TestCase\Model\Filter\Criterion;

use Cake\Database\Expression\QueryExpression;
use Cake\Database\ValueBinder;
use Cake\I18n\Date;
use Cake\TestSuite\TestCase;
use CakeDC\SearchFilter\Filter\AbstractFilter;
use CakeDC\SearchFilter\Model\Filter\Criterion\DateCriterion;

class DateCriterionTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Model\Filter\Criterion\DateCriterion
     */
    protected $dateCriterion;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->dateCriterion = new DateCriterion('test_date');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->dateCriterion);
        parent::tearDown();
    }

    /**
     * Test constructor
     *
     * @return void
     */
    public function testConstructor(): void
    {
        $this->assertInstanceOf(DateCriterion::class, $this->dateCriterion);
    }

    /**
     * Test isApplicable method
     *
     * @return void
     */
    public function testIsApplicable(): void
    {
        $this->assertTrue($this->dateCriterion->isApplicable('2023-05-15', AbstractFilter::COND_EQ));
        $this->assertFalse($this->dateCriterion->isApplicable('', AbstractFilter::COND_EQ));
        $this->assertFalse($this->dateCriterion->isApplicable(null, AbstractFilter::COND_EQ));
    }

    /**
     * Test buildFilter method with '=' condition
     *
     * @return void
     */
    public function testBuildEqualFilter(): void
    {
        $condition = AbstractFilter::COND_EQ;
        $values = ['value' => '2023-05-15'];
        $criteria = ['test_date' => '2023-05-15'];
        $options = [];

        $result = $this->dateCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_date =', $sql);
        $this->assertStringContainsString(':c0', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertEquals('2023-05-15', $bindings[':c0']['value']);
    }

    /**
     * Test buildFilter method with '!=' condition
     *
     * @return void
     */
    public function testBuildNotEqualFilter(): void
    {
        $condition = AbstractFilter::COND_NE;
        $values = ['value' => '2023-05-15'];
        $criteria = ['test_date' => '2023-05-15'];
        $options = [];

        $result = $this->dateCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_date !=', $sql);
        $this->assertStringContainsString(':c0', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertEquals('2023-05-15', $bindings[':c0']['value']);
    }

    /**
     * Test buildFilter method with '>' condition
     *
     * @return void
     */
    public function testBuildGreaterThanFilter(): void
    {
        $condition = AbstractFilter::COND_GT;
        $values = ['value' => '2023-05-15'];
        $criteria = ['test_date' => '2023-05-15'];
        $options = [];

        $result = $this->dateCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_date >', $sql);
        $this->assertStringContainsString(':c0', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertEquals('2023-05-15', $bindings[':c0']['value']);
    }

    /**
     * Test buildFilter method with '>=' condition
     *
     * @return void
     */
    public function testBuildGreaterThanOrEqualFilter(): void
    {
        $condition = AbstractFilter::COND_GE;
        $values = ['value' => '2023-05-15'];
        $criteria = ['test_date' => '2023-05-15'];
        $options = [];

        $result = $this->dateCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_date >=', $sql);
        $this->assertStringContainsString(':c0', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertEquals('2023-05-15', $bindings[':c0']['value']);
    }

    /**
     * Test buildFilter method with '<' condition
     *
     * @return void
     */
    public function testBuildLessThanFilter(): void
    {
        $condition = AbstractFilter::COND_LT;
        $values = ['value' => '2023-05-15'];
        $criteria = ['test_date' => '2023-05-15'];
        $options = [];

        $result = $this->dateCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_date <', $sql);
        $this->assertStringContainsString(':c0', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertEquals('2023-05-15', $bindings[':c0']['value']);
    }

    /**
     * Test buildFilter method with '<=' condition
     *
     * @return void
     */
    public function testBuildLessThanOrEqualFilter(): void
    {
        $condition = AbstractFilter::COND_LE;
        $values = ['value' => '2023-05-15'];
        $criteria = ['test_date' => '2023-05-15'];
        $options = [];

        $result = $this->dateCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_date <=', $sql);
        $this->assertStringContainsString(':c0', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertEquals('2023-05-15', $bindings[':c0']['value']);
    }

    /**
     * Test buildFilter method with 'between' condition
     *
     * @return void
     */
    public function testBuildBetweenFilter(): void
    {
        $condition = AbstractFilter::COND_BETWEEN;
        $values = ['date_from' => '2023-05-01', 'date_to' => '2023-05-31'];
        $criteria = ['test_date' => ['2023-05-01', '2023-05-31']];
        $options = [];

        $result = $this->dateCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_date BETWEEN', $sql);
        $this->assertStringContainsString(':c0 AND :c1', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertArrayHasKey(':c1', $bindings);
        $this->assertEquals(new Date('2023-05-01'), $bindings[':c0']['value']);
        $this->assertEquals(new Date('2023-05-31'), $bindings[':c1']['value']);
    }

    /**
     * Test buildFilter method with 'today' condition
     *
     * @return void
     */
    public function testBuildTodayFilter(): void
    {
        $condition = AbstractFilter::COND_TODAY;
        $values = [];
        $criteria = ['test_date' => 'today'];
        $options = [];

        $result = $this->dateCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_date = (CURRENT_DATE())', $sql);
    }

    /**
     * Test buildFilter method with 'yesterday' condition
     *
     * @return void
     */
    public function testBuildYesterdayFilter(): void
    {
        $condition = AbstractFilter::COND_YESTERDAY;
        $values = [];
        $criteria = ['test_date' => 'yesterday'];
        $options = [];

        $result = $this->dateCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_date = (DATE_ADD(CURRENT_DATE, INTERVAL -1 DAY))', $sql);
    }

    /**
     * Test buildFilter method with 'this week' condition
     *
     * @return void
     */
    public function testBuildThisWeekFilter(): void
    {
        $condition = AbstractFilter::COND_THIS_WEEK;
        $values = [];
        $criteria = ['test_date' => 'this week'];
        $options = [];

        $result = $this->dateCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('CONCAT(CAST(EXTRACT(YEAR FROM test_date) AS  varchar), CAST(EXTRACT(WEEK FROM test_date) AS  varchar)) = (CONCAT(CAST(EXTRACT(YEAR FROM CURRENT_DATE) AS  varchar), CAST(EXTRACT(WEEK FROM CURRENT_DATE) AS  varchar)))', $sql);
    }

    /**
     * Test buildFilter method with 'last week' condition
     *
     * @return void
     */
    public function testBuildLastWeekFilter(): void
    {
        $condition = AbstractFilter::COND_LAST_WEEK;
        $values = [];
        $criteria = ['test_date' => 'last week'];
        $options = [];

        $result = $this->dateCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('CONCAT(CAST(EXTRACT(YEAR FROM test_date) AS  varchar), CAST(EXTRACT(WEEK FROM test_date) AS  varchar)) = (CONCAT(CAST(EXTRACT(YEAR FROM DATE_ADD(CURRENT_DATE, INTERVAL -7 DAY)) AS  varchar), CAST(EXTRACT(WEEK FROM DATE_ADD(CURRENT_DATE, INTERVAL -7 DAY)) AS  varchar)))', $sql);
    }
}
