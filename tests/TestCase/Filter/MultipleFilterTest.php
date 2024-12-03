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
