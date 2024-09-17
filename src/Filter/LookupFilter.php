<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Filter;

use Cake\Routing\Router;

/**
 * LookupFilter class
 *
 * This class extends AbstractFilter and is used for autocomplete-based filtering.
 * It provides functionality for looking up values based on a query string.
 */
class LookupFilter extends AbstractFilter
{
    /**
     * Array of filter properties
     *
     * @var array<string, mixed>
     */
    protected array $properties = [
        'type' => 'autocomplete',
        'idName' => 'id',
        'valueName' => 'name',
        'query' => 'name=%QUERY',
        'wildcard' => '%QUERY',
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
        AbstractFilter::COND_LIKE => 'Like',
    ];

    /**
     * Array of autocomplete route configuration
     *
     * @var array<string, string>
     */
    protected array $autocompleteRoute = [
        'action' => 'autocomplete',
        '_ext' => 'json',
    ];

    /**
     * Array of lookup fields
     *
     * @var array<int, string>
     */
    protected array $lookupFields = ['name', 'title', 'id'];

    /**
     * Get the autocomplete route configuration
     *
     * @return array<string, string>
     */
    public function getAutocompleteRoute(): array
    {
        return $this->autocompleteRoute;
    }

    /**
     * Set the autocomplete route configuration
     *
     * @param array<string, string> $autocompleteRoute The autocomplete route configuration to set
     * @return self
     */
    public function setAutocompleteRoute(array $autocompleteRoute): self
    {
        $this->autocompleteRoute = $autocompleteRoute;

        return $this;
    }

    /**
     * Get the lookup fields
     *
     * @return array<int, string>
     */
    public function getLookupFields(): array
    {
        return $this->lookupFields;
    }

    /**
     * Set the lookup fields
     *
     * @param array<int, string> $lookupFields The lookup fields to set
     * @return self
     */
    public function setLookupFields(array $lookupFields): self
    {
        $this->lookupFields = $lookupFields;

        return $this;
    }

    /**
     * Convert the filter to an array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        if (empty($this->properties['autocompleteUrl'])) {
            $this->properties['autocompleteUrl'] = Router::url($this->getAutocompleteRoute());
        }
        $props = parent::toArray();

        return $props;
    }
}
