<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Test\TestCase\Filter;

use Cake\TestSuite\TestCase;
use CakeDC\SearchFilter\Filter\AbstractFilter;
use CakeDC\SearchFilter\Filter\StringFilter;

/**
 * StringFilter Test Case
 */
class StringFilterTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Filter\StringFilter
     */
    protected $stringFilter;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->stringFilter = new StringFilter();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->stringFilter);
        parent::tearDown();
    }

    /**
     * Test constructor and inheritance
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(AbstractFilter::class, $this->stringFilter);
        $this->assertInstanceOf(StringFilter::class, $this->stringFilter);
    }

    /**
     * Test toArray method
     *
     * @return void
     */
    public function testToArray(): void
    {
        $result = $this->stringFilter->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('type', $result);
        $this->assertEquals('string', $result['type']);
    }

    /**
     * Test getConditions method
     *
     * @return void
     */
    public function testGetConditions(): void
    {
        $conditions = $this->stringFilter->getConditions();

        $this->assertIsArray($conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_EQ, $conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_NE, $conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_LIKE, $conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_IN, $conditions);
    }

    /**
     * Test getType method (inherited from AbstractFilter)
     *
     * @return void
     */
    public function testGetType(): void
    {
        $this->assertEquals('string', $this->stringFilter->getType());
    }

    /**
     * Test setType method (inherited from AbstractFilter)
     *
     * @return void
     */
    public function testSetType(): void
    {
        $this->stringFilter->setType('custom_type');
        $this->assertEquals('custom_type', $this->stringFilter->getType());
    }

    /**
     * Test getProperties method (inherited from AbstractFilter)
     *
     * @return void
     */
    public function testGetProperties(): void
    {
        $properties = $this->stringFilter->getProperties();
        $this->assertIsArray($properties);
        $this->assertArrayHasKey('type', $properties);
        $this->assertEquals('string', $properties['type']);
    }

    /**
     * Test setProperties method (inherited from AbstractFilter)
     *
     * @return void
     */
    public function testSetProperties(): void
    {
        $newProperties = ['custom' => 'value'];
        $this->stringFilter->setProperties($newProperties);
        $properties = $this->stringFilter->getProperties();
        $this->assertArrayHasKey('custom', $properties);
        $this->assertEquals('value', $properties['custom']);
    }

    /**
     * Test getLabel method (inherited from AbstractFilter)
     *
     * @return void
     */
    public function testGetLabel(): void
    {
        $this->assertNull($this->stringFilter->getLabel());
    }

    /**
     * Test setLabel method (inherited from AbstractFilter)
     *
     * @return void
     */
    public function testSetLabel(): void
    {
        $this->stringFilter->setLabel('Custom Label');
        $this->assertEquals('Custom Label', $this->stringFilter->getLabel());
    }
}
