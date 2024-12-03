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
use CakeDC\SearchFilter\Filter\SelectFilter;
use ReflectionClass;

class SelectFilterTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Filter\SelectFilter
     */
    protected $selectFilter;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->selectFilter = new SelectFilter();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->selectFilter);
        parent::tearDown();
    }

    /**
     * Test constructor and inheritance
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(AbstractFilter::class, $this->selectFilter);
        $this->assertInstanceOf(SelectFilter::class, $this->selectFilter);
    }

    /**
     * Test getOptions method
     *
     * @return void
     */
    public function testGetOptions(): void
    {
        $options = $this->selectFilter->getOptions();
        $this->assertIsArray($options);
        $this->assertEmpty($options);
    }

    /**
     * Test setOptions method
     *
     * @return void
     */
    public function testSetOptions(): void
    {
        $options = ['option1' => 'Value 1', 'option2' => 'Value 2'];
        $result = $this->selectFilter->setOptions($options);

        $this->assertInstanceOf(SelectFilter::class, $result);
        $this->assertEquals($options, $this->selectFilter->getOptions());
    }

    /**
     * Test setEmpty method
     *
     * @return void
     */
    public function testSetEmpty(): void
    {
        $emptyValue = 'Please select';
        $result = $this->selectFilter->setEmpty($emptyValue);

        $this->assertInstanceOf(SelectFilter::class, $result);

        $properties = $this->getObjectProperty($this->selectFilter, 'properties');
        $this->assertArrayHasKey('empty', $properties);
        $this->assertEquals($emptyValue, $properties['empty']);
    }

    /**
     * Test toArray method
     *
     * @return void
     */
    public function testToArray(): void
    {
        $options = ['option1' => 'Value 1', 'option2' => 'Value 2'];
        $this->selectFilter->setOptions($options);

        $result = $this->selectFilter->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('options', $result);
        $this->assertEquals($options, $result['options']);
        $this->assertArrayHasKey('type', $result);
        $this->assertEquals('select', $result['type']);
    }

    /**
     * Helper method to get protected/private property of an object
     *
     * @param object $object
     * @param string $propertyName
     * @return mixed
     */
    private function getObjectProperty($object, $propertyName)
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }
}
