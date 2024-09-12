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
use CakeDC\SearchFilter\Model\Filter\Criterion\StringCriterion;

class StringCriterionTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Model\Filter\Criterion\StringCriterion
     */
    protected $stringCriterion;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->stringCriterion = new StringCriterion('test_field');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->stringCriterion);
        parent::tearDown();
    }

    /**
     * Test constructor
     *
     * @return void
     */
    public function testConstructor(): void
    {
        $this->assertInstanceOf(StringCriterion::class, $this->stringCriterion);
    }

    /**
     * Test valid operators
     *
     * @return void
     */
    public function testValidOperators(): void
    {
        $validOperators = [
            AbstractFilter::COND_EQ,
            AbstractFilter::COND_NE,
            AbstractFilter::COND_LIKE,
            AbstractFilter::COND_NOT_LIKE,
            AbstractFilter::COND_IN,
            AbstractFilter::COND_NOT_IN,
        ];
        foreach ($validOperators as $operator) {
            $criterion = new StringCriterion('test_field');
            $this->assertInstanceOf(StringCriterion::class, $criterion);
        }
    }

    /**
     * Test buildFilter method with 'like' condition
     *
     * @return void
     */
    public function testBuildLikeFilter(): void
    {
        $condition = AbstractFilter::COND_LIKE;
        $values = ['value' => 'test_value'];
        $criteria = ['test_field' => 'test_value'];
        $options = [];

        $result = $this->stringCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_field LIKE', $sql);
        $this->assertStringContainsString(':c0', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertEquals('test_value%', $bindings[':c0']['value']);
    }

    /**
     * Test buildFilter method with '=' condition
     *
     * @return void
     */
    public function testBuildEqualFilter(): void
    {
        $condition = AbstractFilter::COND_EQ;
        $values = ['value' => 'test_value'];
        $criteria = ['test_field' => 'test_value'];
        $options = [];

        $result = $this->stringCriterion->buildFilter($condition, $values, $criteria, $options);

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
        $this->assertEquals('test_value', $bindings[':c0']['value']);
    }

    /**
     * Test buildFilter method with '!=' condition
     *
     * @return void
     */
    public function testBuildNotEqualFilter(): void
    {
        $condition = AbstractFilter::COND_NE;
        $values = ['value' => 'test_value'];
        $criteria = ['test_field' => 'test_value'];
        $options = [];

        $result = $this->stringCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_field !=', $sql);
        $this->assertStringContainsString(':c0', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertEquals('test_value', $bindings[':c0']['value']);
    }

    /**
     * Test buildFilter method with 'not like' condition
     *
     * @return void
     */
    public function testBuildNotLikeFilter(): void
    {
        $condition = AbstractFilter::COND_NOT_LIKE;
        $values = ['value' => 'test_value'];
        $criteria = ['test_field' => 'test_value'];
        $options = [];

        $result = $this->stringCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_field NOT LIKE', $sql);
        $this->assertStringContainsString(':c0', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertEquals('test_value%', $bindings[':c0']['value']);
    }

    /**
     * Test isApplicable method
     *
     * @return void
     */
    public function testIsApplicable(): void
    {
        $this->assertTrue($this->stringCriterion->isApplicable('test_value', AbstractFilter::COND_LIKE));
        $this->assertFalse($this->stringCriterion->isApplicable('', AbstractFilter::COND_LIKE));
        $this->assertFalse($this->stringCriterion->isApplicable(null, AbstractFilter::COND_LIKE));
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
        $values = [['test_field' => 'value1'], ['test_field' => 'value2']];
        $result = $this->stringCriterion->getValues($fieldName, $condition, $values);
        $this->assertEquals(['value1', 'value2'], $result);

        $condition = AbstractFilter::COND_NOT_IN;
        $values = [['test_field' => 'value1'], ['test_field' => 'value2']];
        $result = $this->stringCriterion->getValues($fieldName, $condition, $values);
        $this->assertEquals(['value1', 'value2'], $result);

        $condition = AbstractFilter::COND_EQ;
        $values = ['test_field' => 'value'];
        $result = $this->stringCriterion->getValues($fieldName, $condition, $values);
        $this->assertEquals('value', $result);

        $condition = AbstractFilter::COND_EQ;
        $values = ['other_field' => 'value'];
        $result = $this->stringCriterion->getValues($fieldName, $condition, $values);
        $this->assertNull($result);

        $condition = AbstractFilter::COND_IN;
        $values = ['a' => ['test_field' => 'value1'], 'b' => ['test_field' => 'value2']];
        $result = $this->stringCriterion->getValues($fieldName, $condition, $values);
        $this->assertNull($result);
    }

    /**
     * Test buildQueryByCondition method with case-sensitive LIKE
     *
     * @return void
     */
    public function testBuildQueryByConditionLikeCaseSensitive(): void
    {
        $field = 'test_field';
        $condition = AbstractFilter::COND_LIKE;
        $value = 'test_value';
        $options = [];

        $result = $this->stringCriterion->buildQueryByCondition($field, $condition, $value, $options);

        $this->assertIsCallable($result);

        $expr = new QueryExpression();
        $resultExpr = $result($expr);

        $this->assertInstanceOf(QueryExpression::class, $resultExpr);

        $valueBinder = new ValueBinder();
        $sql = $resultExpr->sql($valueBinder);
        $this->assertStringContainsString('test_field LIKE', $sql);
    }

    /**
     * Test buildQueryByCondition method with case-insensitive LIKE
     *
     * @return void
     */
    public function testBuildQueryByConditionLikeCaseInsensitive(): void
    {
        $field = 'test_field';
        $condition = AbstractFilter::COND_LIKE;
        $value = 'test_value';
        $options = ['caseInsensitive' => true];

        $result = $this->stringCriterion->buildQueryByCondition($field, $condition, $value, $options);

        $this->assertIsCallable($result);

        $expr = new QueryExpression();
        $resultExpr = $result($expr);

        $this->assertInstanceOf(QueryExpression::class, $resultExpr);

        $valueBinder = new ValueBinder();
        $sql = $resultExpr->sql($valueBinder);
        $this->assertStringContainsString('ILIKE', $sql);
    }

    /**
     * Test buildQueryByCondition method with case-sensitive NOT LIKE
     *
     * @return void
     */
    public function testBuildQueryByConditionNotLikeCaseSensitive(): void
    {
        $field = 'test_field';
        $condition = AbstractFilter::COND_NOT_LIKE;
        $value = 'test_value';
        $options = [];

        $result = $this->stringCriterion->buildQueryByCondition($field, $condition, $value, $options);

        $this->assertIsCallable($result);

        $expr = new QueryExpression();
        $resultExpr = $result($expr);

        $this->assertInstanceOf(QueryExpression::class, $resultExpr);

        $valueBinder = new ValueBinder();
        $sql = $resultExpr->sql($valueBinder);
        $this->assertStringContainsString('test_field NOT LIKE', $sql);
    }

    /**
     * Test buildQueryByCondition method with case-insensitive NOT LIKE
     *
     * @return void
     */
    public function testBuildQueryByConditionNotLikeCaseInsensitive(): void
    {
        $field = 'test_field';
        $condition = AbstractFilter::COND_NOT_LIKE;
        $value = 'test_value';
        $options = ['caseInsensitive' => true];

        $result = $this->stringCriterion->buildQueryByCondition($field, $condition, $value, $options);

        $this->assertIsCallable($result);

        $expr = new QueryExpression();
        $resultExpr = $result($expr);

        $this->assertInstanceOf(QueryExpression::class, $resultExpr);

        $valueBinder = new ValueBinder();
        $sql = $resultExpr->sql($valueBinder);
        $this->assertStringContainsString('NOT ILIKE', $sql);
    }
}
