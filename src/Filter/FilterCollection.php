<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Filter;

use ArrayIterator;
use Countable;
use Exception;
use IteratorAggregate;
use Traversable;

/**
 * FilterCollection class
 *
 * This class represents a collection of filters and provides methods to manage and interact with them.
 *
 * @implements \IteratorAggregate<string, \CakeDC\SearchFilter\Filter\FilterInterface>
 * @psalm-suppress TooManyTemplateParams
 */
class FilterCollection implements IteratorAggregate, Countable
{
    /**
     * Filter list
     *
     * @var array<string, \CakeDC\SearchFilter\Filter\FilterInterface>
     */
    protected array $filters = [];

    /**
     * Constructor
     *
     * @param array<string, \CakeDC\SearchFilter\Filter\FilterInterface> $filters The map of filters to add to the collection.
     */
    public function __construct(array $filters = [])
    {
        foreach ($filters as $aslias => $filter) {
            $this->add($aslias, $filter);
        }
    }

    /**
     * Add an filter to the collection
     *
     * @param string $alias The filter alias to map.
     * @param \CakeDC\SearchFilter\Filter\FilterInterface $filter The filter to map.
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function add(string $alias, FilterInterface $filter)
    {
        if ($this->has($alias)) {
            throw new Exception(__('Filter %s already registered', $alias));
        }
        $this->filters[$alias] = $filter;
        $filter->setAlias($alias);

        return $this;
    }

    /**
     * Add multiple filters at once.
     *
     * @param array<string, \CakeDC\SearchFilter\Filter\FilterInterface> $filters The map of filters to add to the collection.
     * @return $this
     */
    public function addMany(array $filters)
    {
        foreach ($filters as $alias => $class) {
            $this->add($alias, $class);
        }

        return $this;
    }

    /**
     * Remove an filter from the collection if it exists.
     *
     * @param string $name The named filter.
     * @return $this
     */
    public function remove(string $name)
    {
        unset($this->filters[$name]);

        return $this;
    }

    /**
     * Implementation of IteratorAggregate.
     *
     * @return \Traversable<string, \CakeDC\SearchFilter\Filter\FilterInterface>
     * @psalm-suppress TooManyTemplateParams
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->filters);
    }

    /**
     * Implementation of Countable.
     *
     * Get the number of filters in the collection.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->filters);
    }

    /**
     * Get the list of available filter names.
     *
     * @return array<string> Filter names
     * @psalm-return list<int|string>
     */
    public function keys(): array
    {
        return array_keys($this->filters);
    }

    /**
     * Check for an filter by name.
     *
     * @param string $alias The filter alias to get.
     * @return bool Whether the filter exists.
     */
    public function has(string $alias): bool
    {
        return isset($this->filters[$alias]);
    }

    /**
     * Get the view configuration for the filters.
     *
     * @return array<string,mixed> The view configuration.
     */
    public function getViewConfig(): array
    {
        $result = [];
        $filters = $this->filters;
        usort($filters, function ($a, $b) {
            return strcmp($a->getLabel(), $b->getLabel());
        });
        foreach ($filters as $filter) {
            $alias = $filter->getAlias();
            $result[$alias] = $filter->toArray();
        }

        return $result;
    }

    /**
     * Get the criteria for the filters.
     *
     * @return array<string,mixed> The criteria.
     */
    public function getCriteria(): array
    {
        $result = [];
        foreach ($this->filters as $alias => $filter) {
            $criterion = $filter->getCriterion();
            if ($criterion !== null) {
                $result[$alias] = $filter->getCriterion();
            }
        }

        return $result;
    }
}
