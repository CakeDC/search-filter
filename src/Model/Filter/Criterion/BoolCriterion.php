<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Model\Filter\Criterion;

use Cake\Database\ExpressionInterface;
use Cake\ORM\Query;
use CakeDC\SearchFilter\Filter\AbstractFilter;

class BoolCriterion extends BaseCriterion
{
    /**
     * BoolCriterion constructor.
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
     * @param \Cake\ORM\Query<\Cake\Datasource\EntityInterface> $query
     * @param string|null $condition
     * @param array<string, mixed> $values
     * @param array<string, mixed> $criteria
     * @param array<string, mixed> $options
     * @return \Cake\ORM\Query<\Cake\Datasource\EntityInterface>
     */
    public function __invoke(Query $query, $condition, array $values, array $criteria, $options): Query
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
        $value = $this->getValues('value', AbstractFilter::COND_EQ, $values);

        return $this->buildQueryByCondition($this->field, '=', $value, ['type' => 'integer']);
    }
}
