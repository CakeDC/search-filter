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
