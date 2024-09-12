<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Filter;

/**
 * SelectFilter class
 *
 * This class extends AbstractFilter and is used for select-based filtering.
 * It provides functionality for handling select options and conditions.
 */
class SelectFilter extends AbstractFilter
{
    /**
     * Array of select options
     *
     * @var array<int|string,string>
     */
    protected array $options = [];

    /**
     * Array of filter properties
     *
     * @var array<string, mixed>
     */
    protected array $properties = [
        'type' => 'select',
    ];

    /**
     * Array of filter conditions
     *
     * @var array<string, string>|object|null
     */
    protected array|object|null $conditions = [
        AbstractFilter::COND_EQ => '=',
        AbstractFilter::COND_NE => '≠',
        AbstractFilter::COND_IN => 'In',
        AbstractFilter::COND_NOT_IN => 'Not In',
    ];

    /**
     * Get the select options
     *
     * @return array<int|string,string>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Set the empty option for the select
     *
     * @param string $value The value for the empty option
     * @return self
     */
    public function setEmpty(string $value): self
    {
        $this->setProperty('empty', $value);

        return $this;
    }

    /**
     * Set the select options
     *
     * @param array<int|string,string> $options The options to set
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
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $props = parent::toArray();
        $props['options'] = $this->getOptions();

        return $props;
    }
}
