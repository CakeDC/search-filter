<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Test\TestCase;

use Cake\Database\Schema\TableSchema;
use Cake\Http\ServerRequest;
use Cake\ORM\Table;
use Cake\TestSuite\TestCase;
use CakeDC\SearchFilter\Filter\FilterCollection;
use CakeDC\SearchFilter\Filter\FilterInterface;
use CakeDC\SearchFilter\Filter\FilterRegistry;
use CakeDC\SearchFilter\Manager;
use CakeDC\SearchFilter\Model\Filter\Criterion\CriteriaBuilder;
use ReflectionClass;

class ManagerTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Manager
     */
    protected $manager;

    /**
     * @var \Cake\Http\ServerRequest|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = $this->createMock(ServerRequest::class);
        $this->manager = new Manager($this->request);
    }

    public function testConstructor(): void
    {
        $this->assertInstanceOf(Manager::class, $this->manager);
    }

    public function testFilters(): void
    {
        $filters = $this->manager->filters();
        $this->assertInstanceOf(FilterRegistry::class, $filters);
    }

    public function testCriterion(): void
    {
        $criterion = $this->manager->criterion();
        $this->assertInstanceOf(CriteriaBuilder::class, $criterion);
    }

    public function testLoadFilters(): void
    {
        $filters = $this->manager->filters();
        $this->assertTrue($filters->has('boolean'));
        $this->assertTrue($filters->has('date'));
        $this->assertTrue($filters->has('datetime'));
        $this->assertTrue($filters->has('lookup'));
        $this->assertTrue($filters->has('multiple'));
        $this->assertTrue($filters->has('numeric'));
        $this->assertTrue($filters->has('select'));
        $this->assertTrue($filters->has('string'));
    }

    public function testLoadFilter(): void
    {
        $mockFilter = $this->createMock(FilterInterface::class);

        $mockRegistry = $this->createMock(FilterRegistry::class);
        $mockRegistry->method('load')->willReturn($mockFilter);

        $reflectionManager = new ReflectionClass($this->manager);
        $filtersProperty = $reflectionManager->getProperty('_filters');
        $filtersProperty->setAccessible(true);
        $filtersProperty->setValue($this->manager, $mockRegistry);

        $filter = $this->manager->loadFilter('custom', ['className' => 'CustomFilter']);
        $this->assertInstanceOf(FilterInterface::class, $filter);
    }

    public function testGetFieldBlacklist(): void
    {
        $blacklist = $this->manager->getFieldBlacklist();
        $this->assertIsArray($blacklist);
        $this->assertContains('id', $blacklist);
        $this->assertContains('password', $blacklist);
        $this->assertContains('created', $blacklist);
        $this->assertContains('modified', $blacklist);
    }

    public function testSetFieldBlacklist(): void
    {
        $newBlacklist = ['secret_field', 'another_secret'];
        $result = $this->manager->setFieldBlacklist($newBlacklist);
        $this->assertInstanceOf(Manager::class, $result);
        $this->assertEquals($newBlacklist, $this->manager->getFieldBlacklist());
    }

    public function testNewCollection(): void
    {
        $collection = $this->manager->newCollection();
        $this->assertInstanceOf(FilterCollection::class, $collection);
    }

    public function testAppendFromSchema(): void
    {
        $collection = $this->manager->newCollection();
        $table = $this->createMock(Table::class);
        $schema = new TableSchema('test_table');
        $schema->addColumn('name', ['type' => 'string']);
        $schema->addColumn('age', ['type' => 'integer']);

        $table->method('getSchema')->willReturn($schema);

        $result = $this->manager->appendFromSchema($collection, $table);
        $this->assertInstanceOf(FilterCollection::class, $result);
        $this->assertTrue($result->has('name'));
        $this->assertTrue($result->has('age'));
    }

    public function testFormatSearchData(): void
    {
        $queryParams = [
            'f' => ['0' => 'name', '1' => 'age'],
            'c' => ['0' => 'contains', '1' => 'gt'],
            'v' => ['0' => ['value' => 'John'], '1' => ['value' => '25']],
        ];
        $this->request->expects($this->once())
            ->method('getQuery')
            ->willReturn($queryParams);

        $result = $this->manager->formatSearchData();
        $expected = [
            'name' => ['condition' => 'contains', 'value' => []],
            'age' => ['condition' => 'gt', 'value' => []],
        ];
        $this->assertEquals($expected, $result);
    }

    public function testFormatFinders(): void
    {
        $search = [
            'search' => ['value' => ['value' => 'John']],
            'age' => ['condition' => 'gt', 'value' => ['value' => '25']],
        ];

        $result = $this->manager->formatFinders($search);
        $expected = [
            'search' => 'John',
            'multiple' => [
                'age' => ['condition' => 'gt', 'value' => ['value' => '25']],
            ],
        ];
        $this->assertEquals($expected, $result);
    }
}
