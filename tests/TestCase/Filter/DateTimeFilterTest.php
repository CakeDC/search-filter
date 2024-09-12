<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Test\TestCase\Filter;

use Cake\TestSuite\TestCase;
use CakeDC\SearchFilter\Filter\AbstractFilter;
use CakeDC\SearchFilter\Filter\DateTimeFilter;

class DateTimeFilterTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Filter\DateTimeFilter
     */
    protected $dateTimeFilter;

    public function setUp(): void
    {
        parent::setUp();
        $this->dateTimeFilter = new DateTimeFilter();
    }

    public function tearDown(): void
    {
        unset($this->dateTimeFilter);
        parent::tearDown();
    }

    public function testConstruct(): void
    {
        $this->assertInstanceOf(AbstractFilter::class, $this->dateTimeFilter);
        $this->assertInstanceOf(DateTimeFilter::class, $this->dateTimeFilter);
    }

    public function testToArray(): void
    {
        $result = $this->dateTimeFilter->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('type', $result);
        $this->assertEquals('datetime', $result['type']);
    }

    public function testGetConditions(): void
    {
        $conditions = $this->dateTimeFilter->getConditions();

        $this->assertIsArray($conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_EQ, $conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_GT, $conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_LT, $conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_BETWEEN, $conditions);
    }
}
