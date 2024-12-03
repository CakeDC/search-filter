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

use Cake\ORM\Table;
use Cake\TestSuite\TestCase;
use CakeDC\SearchFilter\Filter\AbstractFilter;
use CakeDC\SearchFilter\Model\Filter\Criterion\InCriterion;
use CakeDC\SearchFilter\Model\Filter\Criterion\StringCriterion;

class InCriterionTest extends TestCase
{
    /**
     * @var \CakeDC\SearchFilter\Model\Filter\Criterion\InCriterion
     */
    protected $inCriterion;

    /**
     * @var \Cake\ORM\Table
     */
    protected $table;

    /**
     * @var \CakeDC\SearchFilter\Model\Filter\Criterion\StringCriterion
     */
    protected $stringCriterion;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->table = $this->getMockBuilder(Table::class)->getMock();
        $this->stringCriterion = new StringCriterion('status');
        $this->inCriterion = new InCriterion('Table.status_id', $this->table, $this->stringCriterion);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->inCriterion);
        unset($this->table);
        unset($this->mockStringCriterion);
        parent::tearDown();
    }

    /**
     * Test constructor
     *
     * @return void
     */
    public function testConstructor(): void
    {
        $this->assertInstanceOf(InCriterion::class, $this->inCriterion);
    }

    /**
     * Test isApplicable method
     *
     * @return void
     */
    public function testIsApplicable(): void
    {
        $this->assertTrue($this->inCriterion->isApplicable(['active', 'pending'], AbstractFilter::COND_IN));
        $this->assertTrue($this->inCriterion->isApplicable(['closed'], AbstractFilter::COND_IN));
        $this->assertFalse($this->inCriterion->isApplicable([], AbstractFilter::COND_IN));
        $this->assertTrue($this->inCriterion->isApplicable('active', AbstractFilter::COND_IN));
        $this->assertFalse($this->inCriterion->isApplicable(null, AbstractFilter::COND_IN));
    }
}
