<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Model\Filter\Criterion;

use Cake\Database\Expression\QueryExpression;

/**
 * CriterionInterface
 *
 * This interface defines the contract for all criterion classes.
 */
interface CriterionInterface
{
    /**
     * Checks if value is applicable for criterion filtering.
     *
     * @param mixed $value Checked value.
     * @param string $condition Condition rule.
     * @return bool
     */
    public function isApplicable(mixed $value, string $condition): bool;

    /**
     * Builds the filter.
     *
     * @param string $condition The condition to apply.
     * @param array<string, mixed> $values The values to filter by.
     * @param array<string, mixed> $criteria Additional criteria.
     * @param array<string, mixed> $options Additional options.
     * @return array<int, \Cake\Database\Expression\QueryExpression>|array<string, mixed>|callable|null A callable that can be used to modify a QueryExpression, or null if not applicable.
     */
    public function buildFilter(string $condition, array $values, array $criteria, array $options = []): array|callable|null;

    /**
     * Builds a query based on the given condition.
     *
     * @param string|\Cake\Database\Expression\QueryExpression $field The field to apply the condition to.
     * @param string $condition The condition to apply.
     * @param mixed $value The value to compare against.
     * @param array<string, mixed> $options Additional options.
     * @return callable A callable that can be used to modify a QueryExpression.
     */
    public function buildQueryByCondition(string|QueryExpression $field, string $condition, mixed $value, array $options = []): callable;
}
