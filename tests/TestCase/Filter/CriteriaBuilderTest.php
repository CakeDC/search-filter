<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Test\TestCase\Model\Filter\Criterion;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakeDC\SearchFilter\Model\Filter\Criterion\AndCriterion;
use CakeDC\SearchFilter\Model\Filter\Criterion\BoolCriterion;
use CakeDC\SearchFilter\Model\Filter\Criterion\CriteriaBuilder;
use CakeDC\SearchFilter\Model\Filter\Criterion\DateCriterion;
use CakeDC\SearchFilter\Model\Filter\Criterion\DateTimeCriterion;
use CakeDC\SearchFilter\Model\Filter\Criterion\InCriterion;
use CakeDC\SearchFilter\Model\Filter\Criterion\NumericCriterion;
use CakeDC\SearchFilter\Model\Filter\Criterion\OrCriterion;
use CakeDC\SearchFilter\Model\Filter\Criterion\StringCriterion;

/**
 * CriteriaBuilder Test Case
 */
class CriteriaBuilderTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Model\Filter\Criterion\CriteriaBuilder
     */
    protected $builder;

    /**
     * @var \Cake\ORM\Table
     */
    protected $table;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->builder = new CriteriaBuilder();
        $this->table = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->builder);
        unset($this->table);
        TableRegistry::getTableLocator()->clear();
        parent::tearDown();
    }

    /**
     * Test and method
     *
     * @return void
     */
    public function testAnd(): void
    {
        $criteria = [
            new StringCriterion('name'),
            new NumericCriterion('age'),
        ];
        $result = $this->builder->and($criteria);
        $this->assertInstanceOf(AndCriterion::class, $result);
    }

    /**
     * Test or method
     *
     * @return void
     */
    public function testOr(): void
    {
        $criteria = [
            new StringCriterion('name'),
            new NumericCriterion('age'),
        ];
        $result = $this->builder->or($criteria);
        $this->assertInstanceOf(OrCriterion::class, $result);
    }

    /**
     * Test in method
     *
     * @return void
     */
    public function testIn(): void
    {
        $field = 'category';
        $criterion = new StringCriterion('category');
        $result = $this->builder->in($field, $this->table, $criterion);
        $this->assertInstanceOf(InCriterion::class, $result);
    }

    /**
     * Test string method
     *
     * @return void
     */
    public function testString(): void
    {
        $result = $this->builder->string('name');
        $this->assertInstanceOf(StringCriterion::class, $result);
    }

    /**
     * Test numeric method
     *
     * @return void
     */
    public function testNumeric(): void
    {
        $result = $this->builder->numeric('age');
        $this->assertInstanceOf(NumericCriterion::class, $result);
    }

    /**
     * Test bool method
     *
     * @return void
     */
    public function testBool(): void
    {
        $result = $this->builder->bool('is_active');
        $this->assertInstanceOf(BoolCriterion::class, $result);
    }

    /**
     * Test date method
     *
     * @return void
     */
    public function testDate(): void
    {
        $result = $this->builder->date('created_date');
        $this->assertInstanceOf(DateCriterion::class, $result);
    }

    /**
     * Test date method with custom format
     *
     * @return void
     */
    public function testDateWithCustomFormat(): void
    {
        $result = $this->builder->date('created_date');
        $this->assertInstanceOf(DateCriterion::class, $result);
    }

    /**
     * Test datetime method
     *
     * @return void
     */
    public function testDatetime(): void
    {
        $result = $this->builder->datetime('created_at');
        $this->assertInstanceOf(DateTimeCriterion::class, $result);
    }

    /**
     * Test datetime method with custom format
     *
     * @return void
     */
    public function testDatetimeWithCustomFormat(): void
    {
        $result = $this->builder->datetime('created_at');
        $this->assertInstanceOf(DateTimeCriterion::class, $result);
    }
}
