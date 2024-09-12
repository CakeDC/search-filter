<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Test\TestCase\Model\Filter\Criterion;

use Cake\Database\Expression\QueryExpression;
use Cake\Database\ValueBinder;
use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase;
use CakeDC\SearchFilter\Filter\AbstractFilter;
use CakeDC\SearchFilter\Model\Filter\Criterion\DateTimeCriterion;

class DateTimeCriterionTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Model\Filter\Criterion\DateTimeCriterion
     */
    protected $dateTimeCriterion;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->dateTimeCriterion = new DateTimeCriterion('test_datetime');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->dateTimeCriterion);
        parent::tearDown();
    }

    /**
     * Test buildFilter method with 'equal' condition
     *
     * @return void
     */
    public function testBuildEqualFilter(): void
    {
        $condition = AbstractFilter::COND_EQ;
        $values = ['value' => '2023-05-15T00:00'];
        $criteria = ['test_datetime' => '2023-05-15T00:00'];
        $options = [];

        $result = $this->dateTimeCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_datetime = :c0', $sql);
        $this->assertEquals('2023-05-15 00:00', $valueBinder->bindings()[':c0']['value']);
    }

    /**
     * Test buildFilter method with 'not equal' condition
     *
     * @return void
     */
    public function testBuildNotEqualFilter(): void
    {
        $condition = AbstractFilter::COND_NE;
        $values = ['value' => '2023-05-15T00:00'];
        $criteria = ['test_datetime' => '2023-05-15T00:00'];
        $options = [];

        $result = $this->dateTimeCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_datetime != :c0', $sql);
        $this->assertEquals('2023-05-15 00:00', $valueBinder->bindings()[':c0']['value']);
    }

    /**
     * Test buildFilter method with 'greater than' condition
     *
     * @return void
     */
    public function testBuildGreaterThanFilter(): void
    {
        $condition = AbstractFilter::COND_GT;
        $values = ['value' => '2023-05-15T00:00'];
        $criteria = ['test_datetime' => '2023-05-15T00:00'];
        $options = [];

        $result = $this->dateTimeCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_datetime > :c0', $sql);
        $this->assertEquals('2023-05-15 00:00', $valueBinder->bindings()[':c0']['value']);
    }

    /**
     * Test buildFilter method with 'less than' condition
     *
     * @return void
     */
    public function testBuildLessThanFilter(): void
    {
        $condition = AbstractFilter::COND_LT;
        $values = ['value' => '2023-05-15T00:00'];
        $criteria = ['test_datetime' => '2023-05-15T00:00'];
        $options = [];

        $result = $this->dateTimeCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_datetime < :c0', $sql);
        $this->assertEquals('2023-05-15 00:00', $valueBinder->bindings()[':c0']['value']);
    }

    /**
     * Test buildFilter method with 'between' condition
     *
     * @return void
     */
    public function testBuildBetweenFilter(): void
    {
        $condition = AbstractFilter::COND_BETWEEN;
        $values = ['date_from' => '2023-05-01T00:00', 'date_to' => '2023-05-31T00:00'];
        $criteria = ['test_datetime' => ['2023-05-01T00:00', '2023-05-31T00:00']];
        $options = [];

        $result = $this->dateTimeCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_datetime BETWEEN :c0 AND :c1', $sql);
        $this->assertEquals(new DateTime('2023-05-01 00:00'), $valueBinder->bindings()[':c0']['value']);
        $this->assertEquals(new DateTime('2023-05-31 00:00'), $valueBinder->bindings()[':c1']['value']);
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
        $criteria = ['test_datetime' => 'today'];
        $options = [];

        $result = $this->dateTimeCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_datetime = (CURRENT_DATE())', $sql);
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
        $criteria = ['test_datetime' => 'yesterday'];
        $options = [];

        $result = $this->dateTimeCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_datetime = (DATE_ADD(CURRENT_DATE, INTERVAL -1 DAY))', $sql);
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
        $criteria = ['test_datetime' => 'this week'];
        $options = [];

        $result = $this->dateTimeCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('CONCAT(CAST(EXTRACT(YEAR FROM test_datetime) AS  varchar), CAST(EXTRACT(WEEK FROM test_datetime) AS  varchar)) = (CONCAT(CAST(EXTRACT(YEAR FROM CURRENT_DATE) AS  varchar), CAST(EXTRACT(WEEK FROM CURRENT_DATE) AS  varchar)))', $sql);
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
        $criteria = ['test_datetime' => 'last week'];
        $options = [];

        $result = $this->dateTimeCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('CONCAT(CAST(EXTRACT(YEAR FROM test_datetime) AS  varchar), CAST(EXTRACT(WEEK FROM test_datetime) AS  varchar)) = (CONCAT(CAST(EXTRACT(YEAR FROM DATE_ADD(CURRENT_DATE, INTERVAL -7 DAY)) AS  varchar), CAST(EXTRACT(WEEK FROM DATE_ADD(CURRENT_DATE, INTERVAL -7 DAY)) AS  varchar)))', $sql);
    }

    /**
     * Test isApplicable method
     *
     * @return void
     */
    public function testIsApplicable(): void
    {
        $this->assertTrue($this->dateTimeCriterion->isApplicable('2023-05-15', AbstractFilter::COND_EQ));
        $this->assertTrue($this->dateTimeCriterion->isApplicable(['2023-05-01', '2023-05-31'], AbstractFilter::COND_BETWEEN));
        $this->assertFalse($this->dateTimeCriterion->isApplicable(null, AbstractFilter::COND_EQ));
        $this->assertFalse($this->dateTimeCriterion->isApplicable('', AbstractFilter::COND_EQ));
        $this->assertFalse($this->dateTimeCriterion->isApplicable([], AbstractFilter::COND_BETWEEN));
    }
}
