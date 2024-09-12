<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Model\Filter\Criterion;

use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query\SelectQuery;

class AndCriterion extends BaseCriterion
{
    /**
     * @var array<\CakeDC\SearchFilter\Model\Filter\Criterion\BaseCriterion>
     */
    protected array $criteria;

    /**
     * AndCriterion constructor.
     *
     * @param array<\CakeDC\SearchFilter\Model\Filter\Criterion\BaseCriterion> $criteria
     */
    public function __construct(array $criteria)
    {
        $this->criteria = $criteria;
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
        $filters = $this->buildFilter($condition, $values, $criteria, $options);
        if (!empty($filters)) {
            return $query->where(function (QueryExpression $exp) use ($filters) {
                return $exp->and($filters);
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
            if ($filter !== null) {
                $filters[] = $filter;
            }
        }

        return $filters;
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
