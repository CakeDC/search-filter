<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Model\Filter\Criterion;

use Cake\Database\Expression\ComparisonExpression;
use Cake\Database\Expression\QueryExpression;
use Cake\Database\ExpressionInterface;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\Utility\Hash;
use CakeDC\SearchFilter\Filter\AbstractFilter;

abstract class BaseCriterion implements CriterionInterface
{
    /**
     * Field name used for searching
     *
     * @var string|\Cake\Database\ExpressionInterface
     */
    protected string|ExpressionInterface $field;

    /**
     * Extract single value or array by value name
     *
     * @param string $fieldName
     * @param string $condition
     * @param array<mixed> $values
     * @return mixed
     */
    public function getValues(string $fieldName, string $condition, array $values): mixed
    {
        if (in_array($condition, [AbstractFilter::COND_IN, AbstractFilter::COND_NOT_IN]) && Hash::numeric(array_keys($values))) {
            return Hash::extract($values, '{n}.' . $fieldName);
        }
        if (array_key_exists($fieldName, $values)) {
            return $values[$fieldName];
        }

        return null;
    }

    /**
     * Build filter method
     *
     * @param string $condition
     * @param array<string, mixed> $values
     * @param array<string, mixed> $criteria
     * @param array<string, mixed> $options
     * @return array<int, \Cake\Database\Expression\QueryExpression>|array<string, mixed>|callable|null A callable that can be used to modify a QueryExpression, or null if not applicable.
     */
    abstract public function buildFilter(string $condition, array $values, array $criteria, array $options = []): array|callable|null;

    /**
     * Checks if value applicable for criterion filtering.
     *
     * @param mixed $value
     * @param string $condition
     * @return bool
     */
    abstract public function isApplicable(mixed $value, string $condition): bool;

    /**
     * Build like style filter.
     *
     * @param string|\Cake\Database\Expression\QueryExpression $field
     * @param string $condition
     * @param mixed $value
     * @param array<string, mixed> $options
     * @return callable
     */
    public function buildQueryByCondition(string|QueryExpression $field, string $condition, mixed $value, array $options = []): callable
    {
        $type = null;
        if (isset($options['type'])) {
            $type = $options['type'];
        }
        if ($condition == AbstractFilter::COND_EQ && is_array($value)) {
            return function (QueryExpression $expr) use ($field, $value, $type): QueryExpression {
                return $expr->in($field, $value, $type);
            };
        }
        if ($condition == AbstractFilter::COND_NE && is_array($value)) {
            return function (QueryExpression $expr) use ($field, $value, $type): QueryExpression {
                return $expr->notIn($field, $value, $type);
            };
        }
        if ($condition == AbstractFilter::COND_EQ) {
            return function (QueryExpression $expr) use ($field, $value, $type): QueryExpression {
                if ($value instanceof FrozenDate) {
                    $value = $value->format('Y-m-d');
                } elseif ($value instanceof FrozenTime) {
                    $value = $value->format('Y-m-d H:i');
                }

                return $expr->eq($field, $value, $type);
            };
        }
        if (
            in_array($condition, [
                AbstractFilter::COND_NE,
                AbstractFilter::COND_GT,
                AbstractFilter::COND_GE,
                AbstractFilter::COND_LT,
                AbstractFilter::COND_LE,
            ])
        ) {
            return function (QueryExpression $expr) use ($field, $value, $type, $condition): QueryExpression {
                if ($value instanceof FrozenDate) {
                    $value = $value->format('Y-m-d');
                } elseif ($value instanceof FrozenTime) {
                    $value = $value->format('Y-m-d H:i');
                }

                return $expr->add(new ComparisonExpression($field, $value, $type, $condition));
            };
        }
        if ($condition == AbstractFilter::COND_IN && is_array($value) && !empty(array_filter($value))) {
            return function (QueryExpression $expr) use ($field, $value, $type): QueryExpression {
                return $expr->in($field, $value, $type);
            };
        }
        if ($condition == AbstractFilter::COND_IN && !is_array($value)) {
            return function (QueryExpression $expr) use ($field, $value, $type): QueryExpression {
                return $expr->in($field, [$value], $type);
            };
        }

        if ($condition == AbstractFilter::COND_NOT_IN && is_array($value) && !empty(array_filter($value))) {
            return function (QueryExpression $expr) use ($field, $value, $type): QueryExpression {
                return $expr->notIn($field, $value, $type);
            };
        }
        if ($condition == AbstractFilter::COND_LIKE || $condition == AbstractFilter::COND_NOT_LIKE) {
            $value = (string)$value;
            if (!isset($options['likeBefore'])) {
                $before = true;
            } else {
                $before = (bool)$options['likeBefore'];
            }
            if (!isset($options['likeAfter'])) {
                $after = true;
            } else {
                $after = (bool)$options['likeAfter'];
            }
            if ($before) {
                $value = '%' . $value;
            }
            if ($after) {
                $value = $value . '%';
            }

            if (isset($options['caseInsensitive']) && $options['caseInsensitive'] === true) {
                return function (QueryExpression $expr) use ($field, $value, $type, $condition) {
                    if ($condition == AbstractFilter::COND_LIKE) {
                        return $expr->add(new ComparisonExpression($field, $value, $type, 'ILIKE'));
                    } else {
                        return $expr->add(new ComparisonExpression($field, $value, $type, 'NOT ILIKE'));
                    }
                };
            }

            return function (QueryExpression $expr) use ($field, $value, $type, $condition): QueryExpression {
                if ($condition == AbstractFilter::COND_LIKE) {
                    return $expr->like($field, $value, $type);
                } else {
                    return $expr->notLike($field, $value, $type);
                }
            };
        }

        return function (QueryExpression $expr): QueryExpression {
            return $expr;
        };
    }
}
