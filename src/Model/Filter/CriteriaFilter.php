<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Model\Filter;

use Cake\ORM\Query\SelectQuery;
use PlumSearch\Model\Filter\AbstractFilter;
use PlumSearch\Model\Filter\Exception\MissingFilterException;
use PlumSearch\Model\FilterRegistry;
use function Cake\I18n\__;

/**
 * Class CriteriaFilter
 *
 * configuration
 *  - name: string
 *  - criteria Map<name, callable(Query $query, string $condition): Query>
 *  - value
 *
 * @package App\Model\Filter
 */
class CriteriaFilter extends AbstractFilter
{
    /**
     * CriteriaFilter constructor.
     *
     * @param \PlumSearch\Model\FilterRegistry $registry
     * @param array<string|int, mixed> $config
     */
    public function __construct(FilterRegistry $registry, array $config = [])
    {
        if (empty($config['criteria'])) {
            throw new MissingFilterException(
                __('Missed "criteria" configuration setting for filter')
            );
        }
        parent::__construct($registry, $config);
    }

    /**
     * Returns query with applied filter
     *
     * @param \Cake\ORM\Query\SelectQuery<\Cake\Datasource\EntityInterface> $query Query.
     * @param string $field Field name.
     * @param string|array $value Field value.
     * @param array<string, mixed> $data Filters values.
     * @return \Cake\ORM\Query\SelectQuery<\Cake\Datasource\EntityInterface>
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     * @phpstan-param array<string, array{condition: string, value: mixed}>|string $value
     */
    protected function _buildQuery(SelectQuery $query, string $field, $value, array $data = []): SelectQuery
    {
        $criteria = $this->getConfig('criteria');
        foreach ($value as $name => $values) {
            $condition = $values['condition'];
            if (array_key_exists($name, $criteria)) {
                $criterion = $criteria[$name];
                if (is_callable($criterion)) {
                    $query = $criterion($query, $condition, $values['value'], $value, $this->getConfig('filterOptions', []));
                }
            }
        }

        return $query;
    }
}
