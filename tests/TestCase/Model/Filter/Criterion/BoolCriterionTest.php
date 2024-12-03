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

namespace CakeDC\SearchFilter\Test\TestCase\Model\Filter\Criterion;

use Cake\Database\Expression\QueryExpression;
use Cake\Database\ValueBinder;
use Cake\TestSuite\TestCase;
use CakeDC\SearchFilter\Filter\AbstractFilter;
use CakeDC\SearchFilter\Model\Filter\Criterion\BoolCriterion;

class BoolCriterionTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Model\Filter\Criterion\BoolCriterion
     */
    protected $boolCriterion;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->boolCriterion = new BoolCriterion('test_field');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->boolCriterion);
        parent::tearDown();
    }

    /**
     * Test constructor
     *
     * @return void
     */
    public function testConstructor(): void
    {
        $this->assertInstanceOf(BoolCriterion::class, $this->boolCriterion);
    }

    /**
     * Test isApplicable method
     *
     * @return void
     */
    public function testIsApplicable(): void
    {
        $this->assertTrue($this->boolCriterion->isApplicable(true, AbstractFilter::COND_EQ));
        $this->assertTrue($this->boolCriterion->isApplicable(false, AbstractFilter::COND_EQ));
        $this->assertTrue($this->boolCriterion->isApplicable(1, AbstractFilter::COND_EQ));
        $this->assertTrue($this->boolCriterion->isApplicable(0, AbstractFilter::COND_EQ));
        $this->assertFalse($this->boolCriterion->isApplicable('', AbstractFilter::COND_EQ));
        $this->assertFalse($this->boolCriterion->isApplicable(null, AbstractFilter::COND_EQ));
    }

    /**
     * Test buildFilter method with '=' condition
     *
     * @return void
     */
    public function testBuildEqualFilter(): void
    {
        $condition = AbstractFilter::COND_EQ;
        $values = ['value' => true];
        $criteria = ['test_field' => true];
        $options = [];

        $result = $this->boolCriterion->buildFilter($condition, $values, $criteria, $options);

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
        $this->assertEquals(true, $bindings[':c0']['value']);
    }

    /**
     * Test buildFilter method with 'in' condition
     *
     * @return void
     */
    public function testBuildInFilter(): void
    {
        $condition = AbstractFilter::COND_IN;
        $values = ['value' => [true, false]];
        $criteria = ['test_field' => [true, false]];
        $options = [];

        $result = $this->boolCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsCallable($result);

        $queryExpression = new QueryExpression();
        $modifiedExpression = $result($queryExpression);

        $this->assertInstanceOf(QueryExpression::class, $modifiedExpression);

        $valueBinder = new ValueBinder();
        $sql = $modifiedExpression->sql($valueBinder);

        $this->assertStringContainsString('test_field IN', $sql);
        $this->assertStringContainsString(':c0', $sql);
        $this->assertStringContainsString(':c1', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertArrayHasKey(':c1', $bindings);
        $this->assertEquals(true, $bindings[':c0']['value']);
        $this->assertEquals(false, $bindings[':c1']['value']);
    }
}
