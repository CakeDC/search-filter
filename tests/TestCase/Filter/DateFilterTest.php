<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Test\TestCase\Filter;

use Cake\TestSuite\TestCase;
use CakeDC\SearchFilter\Filter\AbstractFilter;
use CakeDC\SearchFilter\Filter\DateFilter;

/**
 * DateFilter Test Case
 */
class DateFilterTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Filter\DateFilter
     */
    protected $dateFilter;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->dateFilter = new DateFilter();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->dateFilter);
        parent::tearDown();
    }

    /**
     * Test constructor and inheritance
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(AbstractFilter::class, $this->dateFilter);
        $this->assertInstanceOf(DateFilter::class, $this->dateFilter);
    }

    /**
     * Test default date format
     *
     * @return void
     */
    public function testDefaultDateFormat(): void
    {
        $this->assertEquals('DD/MM/YYYY', $this->dateFilter->getDateFormat());
    }

    /**
     * Test setting custom date format
     *
     * @return void
     */
    public function testSetDateFormat(): void
    {
        $this->dateFilter->setDateFormat('YYYY-MM-DD');
        $this->assertEquals('YYYY-MM-DD', $this->dateFilter->getDateFormat());
    }

    /**
     * Test getConditions method
     *
     * @return void
     */
    public function testGetConditions(): void
    {
        $conditions = $this->dateFilter->getConditions();
        $this->assertIsArray($conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_EQ, $conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_GT, $conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_LT, $conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_BETWEEN, $conditions);
    }

    /**
     * Test toArray method
     *
     * @return void
     */
    public function testToArray(): void
    {
        $result = $this->dateFilter->toArray();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('type', $result);
        $this->assertEquals('date', $result['type']);
        $this->assertArrayHasKey('dateFormat', $result);
        $this->assertEquals('DD/MM/YYYY', $result['dateFormat']);
    }
}
