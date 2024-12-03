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

namespace CakeDC\SearchFilter\Filter;

use CakeDC\SearchFilter\Model\Filter\Criterion\BaseCriterion;

interface FilterInterface
{
    /**
     * Get the properties of the filter.
     *
     * @return array<string, mixed>
     */
    public function getProperties(): array;

    /**
     * Set the properties of the filter.
     *
     * @param array<string, mixed> $properties The properties to set.
     * @return self
     */
    public function setProperties(array $properties): self;

    /**
     * Set a specific property of the filter.
     *
     * @param string $name The name of the property.
     * @param mixed $value The value of the property.
     * @return self
     */
    public function setProperty(string $name, mixed $value): self;

    /**
     * Get the criterion of the filter.
     *
     * @return mixed
     */
    public function getCriterion(): mixed;

    /**
     * Set the criterion of the filter.
     *
     * @param \CakeDC\SearchFilter\Model\Filter\Criterion\BaseCriterion $criterion The criterion to set.
     * @return self
     */
    public function setCriterion(BaseCriterion $criterion): self;

    /**
     * Get the label of the filter.
     *
     * @return string|null
     */
    public function getLabel(): ?string;

    /**
     * Set the label of the filter.
     *
     * @param string $label The label to set.
     * @return self
     */
    public function setLabel(string $label): self;

    /**
     * Get the conditions of the filter.
     *
     * @return array<string, string>|object|null
     */
    public function getConditions(): array|object|null;

    /**
     * Set the conditions of the filter.
     *
     * @param array<string, string>|object $conditions The conditions to set.
     * @return self
     */
    public function setConditions(array|object $conditions): self;

    /**
     * Exclude the 'in' condition from the filter.
     *
     * @return self
     */
    public function excludeIn(): self;

    /**
     * Convert the filter to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;

    /**
     * Create a new instance of the filter.
     *
     * @return self
     */
    public function new(): self;

    /**
     * Get the alias of the filter.
     *
     * @return string|null
     */
    public function getAlias(): ?string;

    /**
     * Set the alias of the filter.
     *
     * @param string $alias The alias to set.
     * @return self
     */
    public function setAlias(string $alias): self;
}
