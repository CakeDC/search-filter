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
use CakeDC\SearchFilter\Filter\NumericFilter;

/**
 * NumericFilter Test Case
 */
class NumericFilterTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Filter\NumericFilter
     */
    protected $numericFilter;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->numericFilter = new NumericFilter();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->numericFilter);
        parent::tearDown();
    }

    /**
     * Test constructor and inheritance
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(AbstractFilter::class, $this->numericFilter);
        $this->assertInstanceOf(NumericFilter::class, $this->numericFilter);
    }

    /**
     * Test toArray method
     *
     * @return void
     */
    public function testToArray(): void
    {
        $result = $this->numericFilter->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('type', $result);
        $this->assertEquals('numeric', $result['type']);
    }

    /**
     * Test getConditions method
     *
     * @return void
     */
    public function testGetConditions(): void
    {
        $conditions = $this->numericFilter->getConditions();

        $this->assertIsArray($conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_EQ, $conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_NE, $conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_GT, $conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_LT, $conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_BETWEEN, $conditions);
    }

    /**
     * Test getLabel method
     *
     * @return void
     */
    public function testGetLabel(): void
    {
        $this->assertNull($this->numericFilter->getLabel());
    }

    /**
     * Test setLabel method
     *
     * @return void
     */
    public function testSetLabel(): void
    {
        $this->numericFilter->setLabel('Custom Label');
        $this->assertEquals('Custom Label', $this->numericFilter->getLabel());
    }

    /**
     * Test getProperties method
     *
     * @return void
     */
    public function testGetProperties(): void
    {
        $properties = $this->numericFilter->getProperties();
        $this->assertIsArray($properties);
        $this->assertArrayHasKey('type', $properties);
        $this->assertEquals('numeric', $properties['type']);
    }

    /**
     * Test setProperties method
     *
     * @return void
     */
    public function testSetProperties(): void
    {
        $newProperties = ['custom' => 'value'];
        $this->numericFilter->setProperties($newProperties);
        $properties = $this->numericFilter->getProperties();
        $this->assertArrayHasKey('custom', $properties);
        $this->assertEquals('value', $properties['custom']);
    }
}
