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
use Cake\ORM\Table;
use Cake\Validation\Validation;
use CakeDC\SearchFilter\Filter\AbstractFilter;

class LookupCriterion extends BaseCriterion
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
            $query->where($filter);
        }

        return $query;
    }

    /**
     * @inheritDoc
     */
    public function buildFilter(string $condition, array $values, array $criteria, array $options = []): array|callable|null
    {
        $value = $this->getValues('id', $condition, $values);
        $stringValue = $this->getValues('value', $condition, $values);

        if ($condition == AbstractFilter::COND_LIKE || $condition == AbstractFilter::COND_NOT_LIKE || (in_array($value, ['null', null]) && !empty($stringValue))) {
            $filter = $this->criterion->buildFilter($condition, ['value' => $stringValue], $criteria, array_merge(['likeBefore' => false], $options));
            if ($filter != null) {
                $subconditionQuery = $this->table->find()
                    ->select([$this->table->aliasField($this->table->getPrimaryKey())])
                    ->where($filter);

                return function (QueryExpression $expr) use ($subconditionQuery): QueryExpression {
                    return $expr->in($this->field, $subconditionQuery);
                };
            }
        } elseif ($this->isIdApplicable($value, $condition)) {
            $firstValue = is_array($value) ? reset($value) : $value;
            $type = Validation::uuid($firstValue) ? 'uuid' : 'integer';

            return $this->buildQueryByCondition($this->field, $condition, $value, array_merge(['type' => $type], $options));
        }

        return null;
    }

    /**
     * Checks if value applicable for criterion filtering.
     *
     * @param mixed $value Checked value.
     * @param string $condition Condition rule.
     * @return bool
     */
    public function isIdApplicable(mixed $value, string $condition): bool
    {
        return is_array($value) && !empty($value) || $value !== '';
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
        return is_array($value) && !empty($value) || $value !== '' && !is_array($value);
    }
}
