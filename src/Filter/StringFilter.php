<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Filter;

/**
 * StringFilter class
 *
 * This class extends AbstractFilter and is used for string-based filtering.
 * It provides conditions for string comparison and matching.
 */
class StringFilter extends AbstractFilter
{
    /**
     * Array of filter properties
     *
     * @var array<string, string>
     */
    protected array $properties = [
        'type' => 'string',
    ];

    /**
     * Array of filter conditions
     *
     * @var array<string, string>|object|null
     */
    protected array|object|null $conditions = [
        AbstractFilter::COND_LIKE => 'Like',
        AbstractFilter::COND_EQ => '=',
        AbstractFilter::COND_NE => '≠',
        AbstractFilter::COND_IN => 'In',
        AbstractFilter::COND_NOT_IN => 'Not In',
    ];

    /**
     * Get the type of the filter
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->properties['type'];
    }

    /**
     * Set the type of the filter
     *
     * @param string $type The type to set
     * @return self
     */
    public function setType(string $type): self
    {
        $this->properties['type'] = $type;

        return $this;
    }
}
