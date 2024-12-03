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
