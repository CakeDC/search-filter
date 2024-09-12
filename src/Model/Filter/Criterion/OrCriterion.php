<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Model\Filter\Criterion;

use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;

class OrCriterion extends BaseCriterion
{
    /**
     * @var \CakeDC\SearchFilter\Model\Filter\Criterion\CriterionInterface[] $criteria
     */
    protected array $criteria;

    /**
     * OrCriterion constructor.
     *
     * @param \CakeDC\SearchFilter\Model\Filter\Criterion\CriterionInterface[] $criteria
     */
    public function __construct(array $criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * Finder method
     *
     * @param \Cake\ORM\Query<\Cake\Datasource\EntityInterface> $query
     * @param string $condition
     * @param array<string, mixed> $values
     * @param array<string, mixed> $criteria
     * @param array<string, mixed> $options
     * @return \Cake\ORM\Query<\Cake\Datasource\EntityInterface>
     */
    public function __invoke(Query $query, string $condition, array $values, array $criteria, array $options): Query
    {
        $filters = $this->buildFilter($condition, $values, $criteria, $options);
        if (!empty($filters)) {
            return $query->where(function (QueryExpression $exp) use ($filters) {
                return $exp->or($filters);
            });
        }

        return $query;
    }

    /**
     * @inheritDoc
     */
    public function buildFilter(string $condition, array $values, array $criteria, array $options = []): array|callable|null
    {
        $filters = [];
        foreach ($this->criteria as $criterion) {
            $filter = $criterion->buildFilter($condition, $values, $criteria, $options);
            if ($filter != null) {
                $filters[] = $filter;
            }
        }
        if (!empty($filters)) {
            return $filters;
        }

        return null;
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
        $result = false;
        foreach ($this->criteria as $c) {
            $result = $result || $c->isApplicable($value, $condition);
        }

        return $result;
    }
}
