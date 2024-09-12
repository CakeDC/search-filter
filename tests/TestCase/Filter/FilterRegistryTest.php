<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Test\TestCase\Filter;

use Cake\TestSuite\TestCase;
use CakeDC\SearchFilter\Filter\Exception\MissingFilterException;
use CakeDC\SearchFilter\Filter\FilterInterface;
use CakeDC\SearchFilter\Filter\FilterRegistry;
use CakeDC\SearchFilter\Filter\NumericFilter;
use CakeDC\SearchFilter\Filter\StringFilter;
use ReflectionMethod;
use RuntimeException;

/**
 * FilterRegistry Test Case
 */
class FilterRegistryTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Filter\FilterRegistry
     */
    protected $filterRegistry;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->filterRegistry = new FilterRegistry();
        $this->filterRegistry->load('numeric', ['className' => NumericFilter::class]);
        $this->filterRegistry->load('string', ['className' => StringFilter::class]);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->filterRegistry);
        parent::tearDown();
    }

    /**
     * Test __get method
     *
     * @return void
     */
    public function testGet(): void
    {
        $stringFilter = $this->filterRegistry->get('string');
        $this->assertInstanceOf(FilterInterface::class, $stringFilter);
    }

    /**
     * Test __get method
     *
     * @return void
     */
    public function testMagicGet(): void
    {
        $retrievedFilter = $this->filterRegistry->string;
        $this->assertInstanceOf(StringFilter::class, $retrievedFilter);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Filter class nonExistentFilterFilter could not be found.');
        $this->filterRegistry->nonExistentFilter;
    }

    /**
     * Test __isset method
     *
     * @return void
     */
    public function testIsset(): void
    {
        $this->assertTrue(isset($this->filterRegistry->string));
    }

    /**
     * Test _throwMissingClassError method
     *
     * @return void
     */
    public function testThrowMissingClassError(): void
    {
        $this->expectException(MissingFilterException::class);
        $this->expectExceptionMessage('Filter class NonExistentFilter could not be found.');

        $method = new ReflectionMethod(FilterRegistry::class, '_throwMissingClassError');
        $method->setAccessible(true);

        $method->invoke($this->filterRegistry, 'NonExistent', null);
    }

    /**
     * Test new method
     *
     * @return void
     */
    public function testNew(): void
    {
        $stringFilter = $this->filterRegistry->new('string');
        $this->assertInstanceOf(FilterInterface::class, $stringFilter);
    }
}
