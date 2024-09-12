<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Model\Filter\Criterion;

use Cake\Database\Expression\QueryExpression;
use Cake\Database\ExpressionInterface;
use Cake\ORM\Query\SelectQuery;
use CakeDC\SearchFilter\Filter\AbstractFilter;

class NumericCriterion extends BaseCriterion
{
    /**
     * NumericCriterion constructor.
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
        return is_array($value) && !empty($value) || $value !== '' && $value !== null;
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
        $filter = null;
        $fieldName = $this->field;
        if ($condition == AbstractFilter::COND_BETWEEN) {
            $from = $this->getValues('from', $condition, $values);
            $to = $this->getValues('to', $condition, $values);
            if ($this->isApplicable($from, $condition) && $this->isApplicable($to, $condition)) {
                $filter = function (QueryExpression $exp) use ($fieldName, $from, $to): QueryExpression {
                    return $exp->between($fieldName, $from, $to, 'integer');
                };
            }
        } else {
            $value = $this->getValues('value', $condition, $values);
            if ($this->isApplicable($value, $condition)) {
                $filter = $this->buildQueryByCondition($fieldName, $condition, $value, array_merge(['type' => 'integer'], $options));
            }
        }

        return $filter;
    }
}
