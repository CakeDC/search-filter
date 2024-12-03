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

namespace CakeDC\SearchFilter\Test\TestCase\Model\Filter\Criterion;

use Cake\Database\Expression\QueryExpression;
use Cake\Database\ValueBinder;
use Cake\TestSuite\TestCase;
use CakeDC\SearchFilter\Filter\AbstractFilter;
use CakeDC\SearchFilter\Model\Filter\Criterion\AndCriterion;
use CakeDC\SearchFilter\Model\Filter\Criterion\StringCriterion;
use Closure;

class AndCriterionTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Model\Filter\Criterion\AndCriterion
     */
    protected $andCriterion;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $nameCriterion = new StringCriterion('name');
        $emailCriterion = new StringCriterion('email');
        $this->andCriterion = new AndCriterion([$nameCriterion, $emailCriterion]);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->andCriterion);
        parent::tearDown();
    }

    /**
     * Test constructor
     *
     * @return void
     */
    public function testConstructor(): void
    {
        $this->assertInstanceOf(AndCriterion::class, $this->andCriterion);
    }

    /**
     * Test buildFilter method
     *
     * @return void
     */
    public function testBuildFilter(): void
    {
        $condition = AbstractFilter::COND_LIKE;
        $values = ['value' => 'john'];
        $criteria = [
            'name' => 'john',
        ];
        $options = [];

        $result = $this->andCriterion->buildFilter($condition, $values, $criteria, $options);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertContainsOnly(Closure::class, $result);

        $queryExpression = new QueryExpression();
        foreach ($result as $closure) {
            $queryExpression = $closure($queryExpression);
        }

        $this->assertInstanceOf(QueryExpression::class, $queryExpression);

        $valueBinder = new ValueBinder();
        $sql = $queryExpression->sql($valueBinder);

        $this->assertStringContainsString('(name LIKE :c0 AND email LIKE :c1)', $sql);

        $bindings = $valueBinder->bindings();
        $this->assertArrayHasKey(':c0', $bindings);
        $this->assertArrayHasKey(':c1', $bindings);
        $this->assertEquals('john%', $bindings[':c0']['value']);
        $this->assertEquals('john%', $bindings[':c1']['value']);
    }

    /**
     * Test isApplicable method
     *
     * @return void
     */
    public function testIsApplicable(): void
    {
        $this->assertTrue($this->andCriterion->isApplicable(['value' => 'John'], AbstractFilter::COND_LIKE));
        $this->assertFalse($this->andCriterion->isApplicable([], AbstractFilter::COND_LIKE));
    }
}
