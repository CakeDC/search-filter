<?php

declare(strict_types=1);

namespace CakeDC\SearchFilter;

/**
 * @deprecated 5.3.0 This class will be removed in a future version of CakePHP
 * Use \CakeDC\SearchFilter\SearchFilterPlugin instead.
 */
class Plugin extends SearchFilterPlugin
{
    /**
     * @param array $config Plugin configuration.
     */
    public function __construct(array $config = [])
    {
        deprecationWarning(
            '5.3.0',
            'The `Plugin` class is deprecated. Use `\CakeDC\SearchFilter\SearchFilterPlugin` instead ' .
                'to comply with CakePHP 5.3+ naming conventions.'
        );
        parent::__construct($config);
    }
}