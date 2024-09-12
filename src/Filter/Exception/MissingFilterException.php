<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Filter\Exception;

use Cake\Core\Exception\CakeException;

/**
 * Used when a filter cannot be found.
 */
class MissingFilterException extends CakeException
{
    /**
     * @inheritDoc
     */
    protected $_messageTemplate = 'Filter class %s could not be found.';
}
