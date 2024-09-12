<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Test\TestCase\Model\Filter\Criterion;

use Cake\Database\Expression\QueryExpression;
use Cake\Database\ValueBinder;
use Cake\TestSuite\TestCase;
use CakeDC\SearchFilter\Filter\AbstractFilter;
use CakeDC\SearchFilter\Model\Filter\Criterion\NumericCriterion;

class NumericCriterionTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Model\Filter\Criterion\NumericCriterion
     */
    protected $numericCriterion;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->numericCriterion = new NumericCriterion('test_field');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->numericCriterion);
        parent::tearDown();
    }

    /**
     * Test constructor
     *
     * @return void
     */
    public function testConstructor(): void
    {
        $this->assertInstanceOf(NumericCriterion::class, $this->numericCriterion);
    }

    /**
     * Test isApplicable method
     *
     * @return void
     */
    public function testIsApplicable(): void
    {
        $this->assertTrue($this->numericCriterion->isApplicable(10, AbstractFilter::COND_EQ));
        $this->assertTrue($this->numericCriterion->isApplicable([1, 2, 3], AbstractFilter::COND_IN));
        $this->assertFalse($this->numericCriterion->isApplicable('', AbstractFilter::COND_EQ));
        $this->assertFalse($this->numericCriterion->isApplicable(null, AbstractFilter::COND_EQ));
    }

    /**
     * Test buildFilter method with '=' condition
     *
     * @return void
     */
    public function testBuildEqualFilter(): void
    {
        $condition = AbstractFilter::COND_EQ;
        $values = ['value' => 10];
        $criteria = ['test_field' => 10];
        $options = [];

        $result = $this->numericCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_field =', $sql);
        $this->assertStringContainsString(':c0', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertEquals(10, $bindings[':c0']['value']);
    }

    /**
     * Test buildFilter method with '>' condition
     *
     * @return void
     */
    public function testBuildGreaterThanFilter(): void
    {
        $condition = AbstractFilter::COND_GT;
        $values = ['value' => 10];
        $criteria = ['test_field' => 10];
        $options = [];

        $result = $this->numericCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_field >', $sql);
        $this->assertStringContainsString(':c0', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertEquals(10, $bindings[':c0']['value']);
    }

    /**
     * Test buildFilter method with '<' condition
     *
     * @return void
     */
    public function testBuildLessThanFilter(): void
    {
        $condition = AbstractFilter::COND_LT;
        $values = ['value' => 10];
        $criteria = ['test_field' => 10];
        $options = [];

        $result = $this->numericCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_field <', $sql);
        $this->assertStringContainsString(':c0', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertEquals(10, $bindings[':c0']['value']);
    }

    /**
     * Test buildFilter method with '<=' condition
     *
     * @return void
     */
    public function testBuildLessThanOrEqualFilter(): void
    {
        $condition = AbstractFilter::COND_LE;
        $values = ['value' => 10];
        $criteria = ['test_field' => 10];
        $options = [];

        $result = $this->numericCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_field <=', $sql);
        $this->assertStringContainsString(':c0', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertEquals(10, $bindings[':c0']['value']);
    }

    /**
     * Test buildFilter method with 'between' condition
     *
     * @return void
     */
    public function testBuildBetweenFilter(): void
    {
        $condition = AbstractFilter::COND_BETWEEN;
        $values = ['from' => 10, 'to' => 20];
        $criteria = ['test_field' => [10, 20]];
        $options = [];

        $result = $this->numericCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_field BETWEEN', $sql);
        $this->assertStringContainsString(':c0 AND :c1', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertArrayHasKey(':c1', $bindings);
        $this->assertEquals(10, $bindings[':c0']['value']);
        $this->assertEquals(20, $bindings[':c1']['value']);
    }

    /**
     * Test buildFilter method with 'in' condition
     *
     * @return void
     */
    public function testBuildInFilter(): void
    {
        $condition = AbstractFilter::COND_IN;
        $values = ['value' => [10, 20, 30]];
        $criteria = ['test_field' => [10, 20, 30]];
        $options = [];

        $result = $this->numericCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_field IN', $sql);
        $this->assertStringContainsString(':c0', $sql);
        $this->assertStringContainsString(':c1', $sql);
        $this->assertStringContainsString(':c2', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertArrayHasKey(':c1', $bindings);
        $this->assertArrayHasKey(':c2', $bindings);
        $this->assertEquals(10, $bindings[':c0']['value']);
        $this->assertEquals(20, $bindings[':c1']['value']);
        $this->assertEquals(30, $bindings[':c2']['value']);
    }

    /**
     * Test buildFilter method with 'not in' condition
     *
     * @return void
     */
    public function testBuildNotInFilter(): void
    {
        $condition = AbstractFilter::COND_NOT_IN;
        $values = ['value' => [10, 20, 30]];
        $criteria = ['test_field' => [10, 20, 30]];
        $options = [];

        $result = $this->numericCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_field NOT IN', $sql);
        $this->assertStringContainsString(':c0', $sql);
        $this->assertStringContainsString(':c1', $sql);
        $this->assertStringContainsString(':c2', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertArrayHasKey(':c1', $bindings);
        $this->assertArrayHasKey(':c2', $bindings);
        $this->assertEquals(10, $bindings[':c0']['value']);
        $this->assertEquals(20, $bindings[':c1']['value']);
        $this->assertEquals(30, $bindings[':c2']['value']);
    }

    /**
     * Test getValues method
     *
     * @return void
     */
    public function testGetValues(): void
    {
        $fieldName = 'test_field';

        $condition = AbstractFilter::COND_IN;
        $values = [['test_field' => 42], ['test_field' => 43]];
        $result = $this->numericCriterion->getValues($fieldName, $condition, $values);
        $this->assertEquals([42, 43], $result);

        $condition = AbstractFilter::COND_NOT_IN;
        $values = [['test_field' => 42], ['test_field' => 43]];
        $result = $this->numericCriterion->getValues($fieldName, $condition, $values);
        $this->assertEquals([42, 43], $result);

        $condition = AbstractFilter::COND_EQ;
        $values = ['test_field' => 42];
        $result = $this->numericCriterion->getValues($fieldName, $condition, $values);
        $this->assertEquals(42, $result);

        $condition = AbstractFilter::COND_EQ;
        $values = ['other_field' => 42];
        $result = $this->numericCriterion->getValues($fieldName, $condition, $values);
        $this->assertNull($result);

        $condition = AbstractFilter::COND_IN;
        $values = ['a' => ['test_field' => 42], 'b' => ['test_field' => 43]];
        $result = $this->numericCriterion->getValues($fieldName, $condition, $values);
        $this->assertNull($result);
    }
}
