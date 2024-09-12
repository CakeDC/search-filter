<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Filter;

use stdClass;

/**
 * BooleanFilter class
 *
 * This class extends AbstractFilter and is used for boolean-based filtering.
 * It provides options for Yes/No selections and handles boolean conditions.
 */
class BooleanFilter extends AbstractFilter
{
    /**
     * Array of boolean options
     *
     * @var array<int|string, string>
     */
    protected array $options = [
        1 => 'Yes',
        0 => 'No',
    ];

    /**
     * Array of filter properties
     *
     * @var array<string, string>
     */
    protected array $properties = [
        'type' => 'select',
    ];

    /**
     * Array of filter conditions
     *
     * @var array<string, string>|object|null
     */
    protected array|object|null $conditions = [];

    /**
     * Get the boolean options
     *
     * @return array<int|string, string>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Set the boolean options
     *
     * @param array<int|string, string> $options The options to set
     * @return self
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Convert the filter to an array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $props = parent::toArray();
        $props['options'] = $this->getOptions();
        $props['condtions'] = new stdClass();

        return $props;
    }
}
