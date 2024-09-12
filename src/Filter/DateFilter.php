<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Filter;

/**
 * DateFilter class
 *
 * This class extends AbstractFilter and is used for date-based filtering.
 * It provides functionality for handling date formats and date-specific conditions.
 */
class DateFilter extends AbstractFilter
{
    /**
     * Array of filter properties
     *
     * @var array<string, mixed>
     */
    protected array $properties = [
        'dateFormat' => 'DD/MM/YYYY',
        'type' => 'date',
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
        AbstractFilter::COND_TODAY => 'Today',
        AbstractFilter::COND_YESTERDAY => 'Yesterday',
        AbstractFilter::COND_THIS_WEEK => 'This week',
        AbstractFilter::COND_LAST_WEEK => 'Last week',
    ];

    /**
     * Get the date format
     *
     * @return string
     */
    public function getDateFormat(): string
    {
        return $this->properties['dateFormat'];
    }

    /**
     * Set the date format
     *
     * @param string $format The date format to set
     * @return self
     */
    public function setDateFormat(string $format): self
    {
        $this->properties['dateFormat'] = $format;

        return $this;
    }
}
