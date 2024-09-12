<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Test\TestCase\Filter;

use Cake\TestSuite\TestCase;
use CakeDC\SearchFilter\Filter\AbstractFilter;
use CakeDC\SearchFilter\Filter\MultipleFilter;

class MultipleFilterTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Filter\MultipleFilter
     */
    protected $multipleFilter;

    public function setUp(): void
    {
        parent::setUp();
        $this->multipleFilter = new MultipleFilter();
    }

    public function tearDown(): void
    {
        unset($this->multipleFilter);
        parent::tearDown();
    }

    public function testConstruct(): void
    {
        $this->assertInstanceOf(AbstractFilter::class, $this->multipleFilter);
        $this->assertInstanceOf(MultipleFilter::class, $this->multipleFilter);
    }

    public function testToArray(): void
    {
        $result = $this->multipleFilter->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('type', $result);
        $this->assertEquals('multiple', $result['type']);
    }

    public function testGetConditions(): void
    {
        $conditions = $this->multipleFilter->getConditions();

        $this->assertIsArray($conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_IN, $conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_NOT_IN, $conditions);
    }
}
