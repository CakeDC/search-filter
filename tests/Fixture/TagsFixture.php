<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * Class TagFixture
 */
class TagsFixture extends TestFixture
{
    /**
     * records property
     *
     * @var array
     */
    public array $records = [
        ['name' => 'tag1'],
        ['name' => 'tag2'],
        ['name' => 'tag3'],
    ];
}
