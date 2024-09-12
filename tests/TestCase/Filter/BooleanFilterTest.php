<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Test\TestCase\Filter;

use Cake\TestSuite\TestCase;
use CakeDC\SearchFilter\Filter\AbstractFilter;
use CakeDC\SearchFilter\Filter\BooleanFilter;

class BooleanFilterTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Filter\BooleanFilter
     */
    protected $booleanFilter;

    public function setUp(): void
    {
        parent::setUp();
        $this->booleanFilter = new BooleanFilter();
    }

    public function tearDown(): void
    {
        unset($this->booleanFilter);
        parent::tearDown();
    }

    public function testConstruct(): void
    {
        $this->assertInstanceOf(AbstractFilter::class, $this->booleanFilter);
        $this->assertInstanceOf(BooleanFilter::class, $this->booleanFilter);
    }

    public function testToArray(): void
    {
        $result = $this->booleanFilter->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('type', $result);
        $this->assertEquals('select', $result['type']);
    }

    public function testGetConditions(): void
    {
        $conditions = $this->booleanFilter->getConditions();

        $this->assertIsArray($conditions);
        foreach ($conditions as $value) {
            $this->assertIsString($value);
        }
    }
}
