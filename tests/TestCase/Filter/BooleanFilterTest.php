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
