<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Filter;

use CakeDC\SearchFilter\Model\Filter\Criterion\BaseCriterion;

/**
 * AbstractFilter class
 *
 * This abstract class implements FilterInterface and provides the base functionality
 * for all filter types. It defines common properties and methods used across different filters.
 */
abstract class AbstractFilter implements FilterInterface
{
    /**
     * Condition constants for various filter operations
     */
    public const COND_EQ = '=';
    public const COND_NE = '!=';
    public const COND_GT = '>';
    public const COND_GE = '>=';
    public const COND_LT = '<';
    public const COND_LE = '<=';
    public const COND_IN = 'in';
    public const COND_NOT_IN = 'notIn';
    public const COND_LIKE = 'like';
    public const COND_NOT_LIKE = 'notLike';
    public const COND_BETWEEN = 'between';
    public const COND_TODAY = 'today';
    public const COND_YESTERDAY = 'yesterday';
    public const COND_THIS_WEEK = 'this_week';
    public const COND_LAST_WEEK = 'last_week';

    /**
     * Array of filter properties
     *
     * @var array<string, mixed>
     */
    protected array $properties = [];

    /**
     * Array or object of filter conditions
     *
     * @var array<string, string>|object|null
     */
    protected array|object|null $conditions = [];

    /**
     * Label of the filter
     *
     * @var string|null
     */
    protected ?string $label = null;

    /**
     * Criterion of the filter
     *
     * @var \CakeDC\SearchFilter\Model\Filter\Criterion\BaseCriterion|null
     */
    protected ?BaseCriterion $criterion = null;

    /**
     * Alias of the filter
     *
     * @var string|null
     */
    protected ?string $alias = null;

    /**
     * Get the properties of the filter.
     *
     * @return array<string, mixed>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * Set the properties of the filter.
     *
     * @param array<string, mixed> $properties The properties to set.
     * @return self
     */
    public function setProperties(array $properties): self
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * Set a specific property of the filter.
     *
     * @param string $name The name of the property.
     * @param mixed $value The value of the property.
     * @return self
     */
    public function setProperty(string $name, mixed $value): self
    {
        $this->properties[$name] = $value;

        return $this;
    }

    /**
     * Get the criterion of the filter.
     *
     * @return mixed
     */
    public function getCriterion(): mixed
    {
        return $this->criterion;
    }

    /**
     * Set the criterion of the filter.
     *
     * @param \CakeDC\SearchFilter\Model\Filter\Criterion\BaseCriterion $criterion The criterion to set.
     * @return self
     */
    public function setCriterion(BaseCriterion $criterion): self
    {
        $this->criterion = $criterion;

        return $this;
    }

    /**
     * Get the label of the filter.
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * Set the label of the filter.
     *
     * @param string $label The label to set.
     * @return self
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get the alias of the filter.
     *
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * Set the alias of the filter.
     *
     * @param string $alias The alias to set.
     * @return self
     */
    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get the conditions of the filter.
     *
     * @return array<string, string>|object|null
     */
    public function getConditions(): array|object|null
    {
        return $this->conditions;
    }

    /**
     * Set the conditions of the filter.
     *
     * @param array<string, string>|object $conditions The conditions to set.
     * @return self
     */
    public function setConditions(array|object $conditions): self
    {
        $this->conditions = $conditions;

        return $this;
    }

    /**
     * Exclude the 'in' condition from the filter.
     *
     * @return self
     */
    public function excludeIn(): self
    {
        unset($this->conditions[AbstractFilter::COND_IN]);
        unset($this->conditions[AbstractFilter::COND_NOT_IN]);

        return $this;
    }

    /**
     * Convert the filter to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $props = $this->getProperties();
        $props['conditions'] = $this->getConditions();
        $props['name'] = $this->getLabel();

        return $props;
    }

    /**
     * Create a new instance of the filter.
     *
     * @return self
     */
    public function new(): self
    {
        return clone $this;
    }
}
