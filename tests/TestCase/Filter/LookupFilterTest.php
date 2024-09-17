<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Test\TestCase\Filter;

use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use CakeDC\SearchFilter\Filter\AbstractFilter;
use CakeDC\SearchFilter\Filter\LookupFilter;

/**
 * LookupFilter Test Case
 */
class LookupFilterTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Filter\LookupFilter
     */
    protected $lookupFilter;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        Router::fullBaseUrl('http://localhost');
        Router::reload();
        $builder = Router::createRouteBuilder('/');
        $builder->scope('/', function ($routes) {
            $routes->connect('/authors/autocomplete', ['controller' => 'Authors', 'action' => 'autocomplete']);
        });

        $this->lookupFilter = new LookupFilter();
        $this->lookupFilter->setAutocompleteRoute([
            'controller' => 'Authors',
            'action' => 'autocomplete',
        ]);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->lookupFilter);
        Router::reload();
        parent::tearDown();
    }

    /**
     * Test constructor and inheritance
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(AbstractFilter::class, $this->lookupFilter);
        $this->assertInstanceOf(LookupFilter::class, $this->lookupFilter);
    }

    /**
     * Test default properties
     *
     * @return void
     */
    public function testDefaultProperties(): void
    {
        $properties = $this->lookupFilter->getProperties();
        $this->assertEquals('autocomplete', $properties['type']);
        $this->assertEquals('id', $properties['idName']);
        $this->assertEquals('name', $properties['valueName']);
        $this->assertEquals('name=%QUERY', $properties['query']);
        $this->assertEquals('%QUERY', $properties['wildcard']);
    }

    /**
     * Test getAutocompleteRoute method
     *
     * @return void
     */
    public function testGetAutocompleteRoute(): void
    {
        $route = $this->lookupFilter->getAutocompleteRoute();
        $this->assertIsArray($route);
        $this->assertEquals('autocomplete', $route['action']);
    }

    /**
     * Test setAutocompleteRoute method
     *
     * @return void
     */
    public function testSetAutocompleteRoute(): void
    {
        $newRoute = ['action' => 'customAutocomplete', '_ext' => 'xml'];
        $this->lookupFilter->setAutocompleteRoute($newRoute);
        $this->assertEquals($newRoute, $this->lookupFilter->getAutocompleteRoute());
    }

    /**
     * Test getLookupFields method
     *
     * @return void
     */
    public function testGetLookupFields(): void
    {
        $fields = $this->lookupFilter->getLookupFields();
        $this->assertIsArray($fields);
        $this->assertEquals(['name', 'title', 'id'], $fields);
    }

    /**
     * Test setLookupFields method
     *
     * @return void
     */
    public function testSetLookupFields(): void
    {
        $newFields = ['custom_name', 'custom_id'];
        $this->lookupFilter->setLookupFields($newFields);
        $this->assertEquals($newFields, $this->lookupFilter->getLookupFields());
    }

    /**
     * Test getConditions method
     *
     * @return void
     */
    public function testGetConditions(): void
    {
        $conditions = $this->lookupFilter->getConditions();
        $this->assertIsArray($conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_EQ, $conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_NE, $conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_IN, $conditions);
        $this->assertArrayHasKey(AbstractFilter::COND_LIKE, $conditions);
    }

    /**
     * Test toArray method
     *
     * @return void
     */
    public function testToArray(): void
    {
        $result = $this->lookupFilter->toArray();

        $this->assertArrayHasKey('autocompleteUrl', $result);
        $this->assertEquals('/authors/autocomplete', $result['autocompleteUrl']);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('type', $result);
        $this->assertEquals('autocomplete', $result['type']);
        $this->assertArrayHasKey('idName', $result);
        $this->assertArrayHasKey('valueName', $result);
        $this->assertArrayHasKey('query', $result);
        $this->assertArrayHasKey('wildcard', $result);
    }
}
