<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Model\Filter\Criterion;

use Cake\Database\ExpressionInterface;
use Cake\ORM\Table;

/**
 * Contains methods related to generating criteria.
 * This acts as a factory for Criterion objects.
 */
class CriteriaBuilder
{
    /**
     * Returns an AndCriterion.
     *
     * @param \CakeDC\SearchFilter\Model\Filter\Criterion\BaseCriterion[] $criteria
     * @return \CakeDC\SearchFilter\Model\Filter\Criterion\AndCriterion
     */
    public function and(array $criteria): AndCriterion
    {
        return new AndCriterion($criteria);
    }

    /**
     * Returns an InCriterion.
     *
     * @param string|\Cake\Database\ExpressionInterface $field
     * @param \Cake\ORM\Table $table
     * @param \CakeDC\SearchFilter\Model\Filter\Criterion\BaseCriterion $criterion
     * @return \CakeDC\SearchFilter\Model\Filter\Criterion\InCriterion
     */
    public function in(string|ExpressionInterface $field, Table $table, BaseCriterion $criterion): InCriterion
    {
        return new InCriterion($field, $table, $criterion);
    }

    /**
     * Returns an OrCriterion.
     *
     * @param \CakeDC\SearchFilter\Model\Filter\Criterion\BaseCriterion[] $criteria
     * @return \CakeDC\SearchFilter\Model\Filter\Criterion\OrCriterion
     */
    public function or(array $criteria): OrCriterion
    {
        return new OrCriterion($criteria);
    }

    /**
     * Returns a StringCriterion.
     *
     * @param string|\Cake\Database\ExpressionInterface $field
     * @return \CakeDC\SearchFilter\Model\Filter\Criterion\StringCriterion
     */
    public function string(string|ExpressionInterface $field): StringCriterion
    {
        return new StringCriterion($field);
    }

    /**
     * Returns a NumericCriterion.
     *
     * @param string|\Cake\Database\ExpressionInterface $field
     * @return \CakeDC\SearchFilter\Model\Filter\Criterion\NumericCriterion
     */
    public function numeric(string|ExpressionInterface $field): NumericCriterion
    {
        return new NumericCriterion($field);
    }

    /**
     * Returns a LookupCriterion.
     *
     * @param string|\Cake\Database\ExpressionInterface $field
     * @param \Cake\ORM\Table $table
     * @param \CakeDC\SearchFilter\Model\Filter\Criterion\BaseCriterion $criterion
     * @return \CakeDC\SearchFilter\Model\Filter\Criterion\LookupCriterion
     */
    public function lookup(string|ExpressionInterface $field, Table $table, BaseCriterion $criterion): LookupCriterion
    {
        return new LookupCriterion($field, $table, $criterion);
    }

    /**
     * Returns a BoolCriterion.
     *
     * @param string|\Cake\Database\ExpressionInterface $field
     * @return \CakeDC\SearchFilter\Model\Filter\Criterion\BoolCriterion
     */
    public function bool(string|ExpressionInterface $field): BoolCriterion
    {
        return new BoolCriterion($field);
    }

    /**
     * Returns a DateCriterion.
     *
     * @param string|\Cake\Database\ExpressionInterface $field
     * @param string $format
     * @return \CakeDC\SearchFilter\Model\Filter\Criterion\DateCriterion
     */
    public function date(string|ExpressionInterface $field, string $format = 'Y-m-d'): DateCriterion
    {
        return new DateCriterion($field, $format);
    }

    /**
     * Returns a DateTimeCriterion.
     *
     * @param string|\Cake\Database\ExpressionInterface $field
     * @param string $format
     * @return \CakeDC\SearchFilter\Model\Filter\Criterion\DateTimeCriterion
     */
    public function datetime(string|ExpressionInterface $field, string $format = 'Y-m-d\TH:i'): DateTimeCriterion
    {
        return new DateTimeCriterion($field, $format);
    }
}
