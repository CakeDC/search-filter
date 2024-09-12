<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Model\Filter\Criterion;

use Cake\Database\Expression\QueryExpression;
use Cake\Database\ExpressionInterface;
use Cake\ORM\Query;
use Cake\ORM\Table;

class InCriterion extends BaseCriterion
{
    /**
     * Table used for nested query
     *
     * @var \Cake\ORM\Table
     */
    protected Table $table;

    /**
     * @var \CakeDC\SearchFilter\Model\Filter\Criterion\BaseCriterion $criterion
     */
    protected BaseCriterion $criterion;

    /**
     * InCriterion constructor.
     *
     * @param string|\Cake\Database\ExpressionInterface $field
     * @param \Cake\ORM\Table $table
     * @param \CakeDC\SearchFilter\Model\Filter\Criterion\BaseCriterion $criterion
     */
    public function __construct(string|ExpressionInterface $field, Table $table, BaseCriterion $criterion)
    {
        $this->field = $field;
        $this->table = $table;
        $this->criterion = $criterion;
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
        $filter = $this->buildFilter($condition, $values, $criteria, $options);
        if ($filter !== null) {
            return $query->where($filter);
        }

        return $query;
    }

    /**
     * @inheritDoc
     */
    public function buildFilter(string $condition, array $values, array $criteria, array $options = []): array|callable|null
    {
        $filter = $this->criterion->buildFilter($condition, $values, $criteria, $options);
        if ($filter !== null) {
            $subconditionQuery = $this->table->find()
                ->select([$this->table->aliasField($this->table->getPrimaryKey())])
                ->where($filter);

            return function (QueryExpression $expr) use ($subconditionQuery): QueryExpression {
                return $expr->in($this->field, $subconditionQuery);
            };
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
        return $this->criterion->isApplicable($value, $condition);
    }
}
