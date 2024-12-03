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

namespace CakeDC\SearchFilter\Model\Filter\Criterion;

use Cake\Database\ExpressionInterface;
use Cake\I18n\Date;
use Cake\I18n\DateTime;

class DateTimeCriterion extends DateCriterion
{
    /**
     * Database type
     *
     * @var string
     */
    protected string $dbType = 'datetime';

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
     * @return \Cake\I18n\Date|\Cake\I18n\DateTime
     */
    protected function prepareTime(string $dateStr): DateTime|Date
    {
        return DateTime::createFromFormat($this->format, $dateStr);
    }
}
