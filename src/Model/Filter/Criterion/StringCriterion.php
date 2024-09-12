<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Model\Filter\Criterion;

use Cake\Database\ExpressionInterface;
use Cake\ORM\Query\SelectQuery;

class StringCriterion extends BaseCriterion
{
    /**
     * StringCriterion constructor.
     *
     * @param string|\Cake\Database\ExpressionInterface $field
     */
    public function __construct(string|ExpressionInterface $field)
    {
        $this->field = $field;
    }

    /**
     * Checks if value applicable for criterion filtering.
     *
     * @param mixed $value
     * @param string $condition
     * @return bool
     */
    public function isApplicable(mixed $value, string $condition): bool
    {
        return is_array($value) && !empty($value) || $value !== '' && $value !== null && !is_array($value);
    }

    /**
     * Finder method
     *
     * @param \Cake\ORM\Query\SelectQuery<\Cake\Datasource\EntityInterface> $query
     * @param string $condition
     * @param array<string, mixed> $values
     * @param array<string, mixed> $criteria
     * @param array<string, mixed> $options
     * @return \Cake\ORM\Query\SelectQuery<\Cake\Datasource\EntityInterface>
     */
    public function __invoke(SelectQuery $query, string $condition, array $values, array $criteria, array $options): SelectQuery
    {
        $filter = $this->buildFilter($condition, $values, $criteria, $options);
        if (!empty($filter)) {
            return $query->where($filter);
        }

        return $query;
    }

    /**
     * @inheritDoc
     */
    public function buildFilter(string $condition, array $values, array $criteria, array $options = []): array|callable|null
    {
        $value = $this->getValues('value', $condition, $values);

        return $this->buildQueryByCondition($this->field, $condition, $value, array_merge(['likeBefore' => false, 'likeAfter' => true], $options));
    }
}
