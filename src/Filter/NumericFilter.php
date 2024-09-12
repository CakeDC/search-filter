<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Filter;

/**
 * NumericFilter class
 *
 * This class extends AbstractFilter and is used for numeric-based filtering.
 * It provides conditions for numeric comparisons and ranges.
 */
class NumericFilter extends AbstractFilter
{
    /**
     * Array of filter properties
     *
     * @var array<string, mixed>
     */
    protected array $properties = [
        'type' => 'numeric',
    ];

    /**
     * Array of filter conditions
     *
     * @var array<string, string>|object|null
     */
    protected array|object|null $conditions = [
        AbstractFilter::COND_EQ => '=',
        AbstractFilter::COND_NE => '≠',
        AbstractFilter::COND_GT => '>',
        AbstractFilter::COND_GE => '>=',
        AbstractFilter::COND_LT => '<',
        AbstractFilter::COND_LE => '<=',
        AbstractFilter::COND_BETWEEN => 'Between',
    ];
}
