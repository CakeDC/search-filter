<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Model\Filter\Criterion;

use Cake\Database\Expression\FunctionExpression;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Database\Expression\QueryExpression;
use Cake\Database\ExpressionInterface;
use Cake\Database\FunctionsBuilder;
use Cake\I18n\Date;
use Cake\I18n\DateTime;
use Cake\ORM\Query\SelectQuery;
use CakeDC\SearchFilter\Filter\AbstractFilter;

class DateCriterion extends BaseCriterion
{
    /**
     * Date format
     *
     * @var string
     */
    protected string $format;

    /**
     * Functions Builder
     *
     * @var \Cake\Database\FunctionsBuilder
     */
    protected FunctionsBuilder $func;

    /**
     * Database type
     *
     * @var string
     */
    protected string $dbType = 'date';

    /**
     * DateCriterion constructor.
     *
     * @param string|\Cake\Database\ExpressionInterface $field
     * @param string $format
     */
    public function __construct(string|ExpressionInterface $field, string $format = 'Y-m-d')
    {
        $this->field = $field;
        $this->format = $format;
        $this->func = new FunctionsBuilder();
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
        return is_array($value) && !empty($value) || ($value !== '' && $value !== null && !is_array($value));
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
        return $this->buildCondition($this->field, $condition, $values, $options);
    }

    /**
     * Build finder condition
     *
     * @param string|\Cake\Database\ExpressionInterface $fieldName
     * @param string $condition
     * @param array<mixed> $values
     * @param array<string, mixed> $options
     * @return callable|null
     */
    public function buildCondition(string|ExpressionInterface $fieldName, string $condition, array $values, array $options = []): ?callable
    {
        if ($this->dbType === 'date') {
            $fieldName = $this->ensureFieldIsDate($fieldName);
        }

        if ($condition == AbstractFilter::COND_BETWEEN) {
            $from = $this->getValues('date_from', $condition, $values);
            $to = $this->getValues('date_to', $condition, $values);

            $from = $this->isApplicable($from, $condition) ? $this->prepareTime($from) : null;
            $to = $this->isApplicable($to, $condition) ? $this->prepareTime($to) : null;

            if (!empty($from) && !empty($to)) {
                return function (QueryExpression $exp) use ($fieldName, $from, $to): QueryExpression {
                    return $exp->between($fieldName, $from, $to, $this->dbType);
                };
            } elseif (!empty($from)) {
                return function (QueryExpression $exp) use ($fieldName, $from): QueryExpression {
                    return $exp->gte($fieldName, $from, $this->dbType);
                };
            } elseif (!empty($to)) {
                return function (QueryExpression $exp) use ($fieldName, $to): QueryExpression {
                    return $exp->lte($fieldName, $to, $this->dbType);
                };
            }

            return null;
        } elseif ($condition == AbstractFilter::COND_TODAY) {
            $value = $this->func->now('date');
            $condition = AbstractFilter::COND_EQ;
        } elseif ($condition == AbstractFilter::COND_YESTERDAY) {
            $value = $this->func->dateAdd('CURRENT_DATE', -1, 'DAY');
            $condition = AbstractFilter::COND_EQ;
        } elseif ($condition == AbstractFilter::COND_THIS_WEEK) {
            if (is_string($fieldName)) {
                $fieldName = new IdentifierExpression($fieldName);
            }
            $fieldName = $this->buildYearWeekExpression($fieldName);
            $value = $this->buildYearWeekExpression('CURRENT_DATE');
            $condition = AbstractFilter::COND_EQ;
        } elseif ($condition == AbstractFilter::COND_LAST_WEEK) {
            if (is_string($fieldName)) {
                $fieldName = new IdentifierExpression($fieldName);
            }
            $fieldName = $this->buildYearWeekExpression($fieldName);
            $value = $this->buildYearWeekExpression(
                $this->func->dateAdd('CURRENT_DATE', -7, 'DAY')
            );
            $condition = AbstractFilter::COND_EQ;
        } else {
            $value = $this->getValues('value', $condition, $values);
            if (!$this->isApplicable($value, $condition)) {
                return null;
            }
            $value = $this->prepareTime($value);
        }

        return $this->buildQueryByCondition($fieldName, $condition, $value, ['type' => $this->dbType]);
    }

    /**
     * Create an expression that simulate the result from the mysql function `yearweek` that works on all drivers
     *
     * @param string|\Cake\Database\ExpressionInterface $value can be a table field or a date
     * @return \Cake\Database\Expression\FunctionExpression
     */
    protected function buildYearWeekExpression(string|ExpressionInterface $value): FunctionExpression
    {
        $extractYear = (new FunctionExpression('CAST'))
            ->setConjunction(' AS ')
            ->add([
                $this->func->extract('YEAR', $value),
                'varchar' => 'literal',
            ]);
        $extractWeek = (new FunctionExpression('CAST'))
            ->setConjunction(' AS ')
            ->add([
                $this->func->extract('WEEK', $value),
                'varchar' => 'literal',
            ]);

        return $this->func->concat([$extractYear, $extractWeek]);
    }

    /**
     * Create a date/time object from a string
     *
     * @param string $dateStr
     * @return \Cake\I18n\Date|\Cake\I18n\DateTime
     */
    protected function prepareTime(string $dateStr): DateTime|Date
    {
        return Date::createFromFormat($this->format, $dateStr);
    }

    /**
     * Ensure the field is converted to a date if it's a datetime
     *
     * @param string|\Cake\Database\ExpressionInterface $field
     * @return \Cake\Database\ExpressionInterface
     */
    protected function ensureFieldIsDate(string|ExpressionInterface $field): ExpressionInterface
    {
        if (is_string($field)) {
            $field = new IdentifierExpression($field);
        }

        return new FunctionExpression('DATE', [$field], [], 'date');
    }
}
