<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Model\Filter\Criterion;

use Cake\Database\ExpressionInterface;
use Cake\I18n\FrozenTime;

class DateTimeCriterion extends DateCriterion
{
    /**
     * DateCriterion constructor.
     *
     * @param string|\Cake\Database\ExpressionInterface $field
     * @param string $format
     */
    public function __construct(string|ExpressionInterface $field, string $format = 'Y-m-d\TH:i')
    {
        parent::__construct($field, $format);
        $this->format = $format;
    }

    /**
     * Create a date/time object from a string
     *
     * @param string $dateStr
     * @return \DateTimeInterface
     */
    protected function prepareTime(string $dateStr): \DateTimeInterface
    {
        return FrozenTime::createFromFormat($this->format, $dateStr);
    }
}
