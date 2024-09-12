<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Test\TestCase\Filter;

use ArrayIterator;
use Cake\TestSuite\TestCase;
use CakeDC\SearchFilter\Filter\FilterCollection;
use CakeDC\SearchFilter\Filter\NumericFilter;
use CakeDC\SearchFilter\Filter\StringFilter;
use CakeDC\SearchFilter\Model\Filter\Criterion\NumericCriterion;
use CakeDC\SearchFilter\Model\Filter\Criterion\StringCriterion;

/**
 * FilterCollection Test Case
 */
class FilterCollectionTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Filter\FilterCollection
     */
    protected $filterCollection;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->filterCollection = new FilterCollection();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->filterCollection);
        parent::tearDown();
    }

    /**
     * Test constructor
     *
     * @return void
     */
    public function testConstructor(): void
    {
        $stringFilter = new StringFilter();
        $collection = new FilterCollection(['test' => $stringFilter]);
        $this->assertCount(1, $collection);
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd(): void
    {
        $stringFilter = new StringFilter();
        $this->filterCollection->add('test', $stringFilter);
        $this->assertCount(1, $this->filterCollection);
    }

    /**
     * Test getCriteria method
     *
     * @return void
     */
    public function testGetCriteria(): void
    {
        $stringFilter = new StringFilter();
        $numericFilter = new NumericFilter();

        $stringCriterion = new StringCriterion('test');
        $numericCriterion = new NumericCriterion('test2');

        $stringFilter->setCriterion($stringCriterion);
        $numericFilter->setCriterion($numericCriterion);

        $this->filterCollection->add('test', $stringFilter);
        $this->filterCollection->add('test2', $numericFilter);

        $criteria = $this->filterCollection->getCriteria();

        $this->assertCount(2, $criteria);
        $this->assertSame(
            [
                'test' => $stringCriterion,
                'test2' => $numericCriterion,
            ],
            $criteria
        );
    }

    /**
     * Test getViewConfig method
     *
     * @return void
     */
    public function testGetViewConfig(): void
    {
        $stringFilter = new StringFilter();
        $numericFilter = new NumericFilter();

        $stringFilter->setLabel('String Filter');
        $numericFilter->setLabel('Numeric Filter');

        $this->filterCollection->add('test', $stringFilter);
        $this->filterCollection->add('test2', $numericFilter);

        $viewConfig = $this->filterCollection->getViewConfig();

        $this->assertCount(2, $viewConfig);
        $this->assertEquals('String Filter', $viewConfig['test']['name']);
        $this->assertEquals('Numeric Filter', $viewConfig['test2']['name']);
    }

    /**
     * Test sorting in getViewConfig method
     *
     * @return void
     */
    public function testGetViewConfigSorting(): void
    {
        $filter1 = new StringFilter();
        $filter2 = new StringFilter();
        $filter3 = new StringFilter();

        $filter1->setLabel('C Filter');
        $filter2->setLabel('A Filter');
        $filter3->setLabel('B Filter');

        $this->filterCollection->add('test1', $filter1);
        $this->filterCollection->add('test2', $filter2);
        $this->filterCollection->add('test3', $filter3);

        $viewConfig = $this->filterCollection->getViewConfig();

        $labels = array_column($viewConfig, 'name');
        $this->assertEquals(['A Filter', 'B Filter', 'C Filter'], $labels);
    }

    /**
     * Test getIterator method
     *
     * @return void
     */
    public function testGetIterator(): void
    {
        $stringFilter = new StringFilter();
        $numericFilter = new NumericFilter();
        $this->filterCollection->add('test1', $stringFilter);
        $this->filterCollection->add('test2', $numericFilter);

        $iterator = $this->filterCollection->getIterator();
        $this->assertInstanceOf(ArrayIterator::class, $iterator);
        $this->assertCount(2, $iterator);
    }

    /**
     * Test count method
     *
     * @return void
     */
    public function testCount(): void
    {
        $this->assertCount(0, $this->filterCollection);
        $stringFilter = new StringFilter();
        $this->filterCollection->add('test', $stringFilter);
        $this->assertCount(1, $this->filterCollection);
    }
}
