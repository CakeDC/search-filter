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
use CakeDC\SearchFilter\Model\Filter\Criterion\OrCriterion;
use CakeDC\SearchFilter\Model\Filter\Criterion\StringCriterion;
use Closure;

class OrCriterionTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Model\Filter\Criterion\OrCriterion
     */
    protected $orCriterion;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $nameCriterion = new StringCriterion('name');
        $emailCriterion = new StringCriterion('email');
        $this->orCriterion = new OrCriterion([$nameCriterion, $emailCriterion]);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->orCriterion);
        parent::tearDown();
    }

    /**
     * Test constructor
     *
     * @return void
     */
    public function testConstructor(): void
    {
        $this->assertInstanceOf(OrCriterion::class, $this->orCriterion);
    }

    /**
     * Test buildFilter method
     *
     * @return void
     */
    public function testBuildFilter(): void
    {
        $condition = AbstractFilter::COND_LIKE;
        $values = ['value' => 'john'];
        $criteria = [
            'name_email' => 'john',
        ];
        $options = [];

        $result = $this->orCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertContainsOnly(Closure::class, $result);

        $queryExpression = new QueryExpression();
        $queryExpression->setConjunction('OR');
        foreach ($result as $closure) {
            $queryExpression = $closure($queryExpression);
        }

        $this->assertInstanceOf(QueryExpression::class, $queryExpression);

        $valueBinder = new ValueBinder();
        $sql = $queryExpression->sql($valueBinder);

        $this->assertStringContainsString('(name LIKE :c0 OR email LIKE :c1)', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertArrayHasKey(':c1', $bindings);
        $this->assertEquals('john%', $bindings[':c0']['value']);
        $this->assertEquals('john%', $bindings[':c1']['value']);
    }

    /**
     * Test isApplicable method
     *
     * @return void
     */
    public function testIsApplicable(): void
    {
        $this->assertTrue($this->orCriterion->isApplicable(['value' => 'John'], AbstractFilter::COND_LIKE));
        $this->assertFalse($this->orCriterion->isApplicable([], AbstractFilter::COND_LIKE));
    }
}
