<?php
declare(strict_types=1);

/**
 * Copyright 2024 - 2024, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2018, Cake Development Corporation (https://www.cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\SearchFilter\Model\Filter;

use Cake\ORM\Query;
use PlumSearch\Model\Filter\AbstractFilter;
use PlumSearch\Model\Filter\Exception\MissingFilterException;
use PlumSearch\Model\FilterRegistry;

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
     * @param \Cake\ORM\Query<\Cake\Datasource\EntityInterface> $query Query.
     * @param string $field Field name.
     * @param array<string, mixed> $value Field value.
     * @param array<string, mixed> $data Filters values.
     * @return \Cake\ORM\Query<\Cake\Datasource\EntityInterface>
     */
    protected function _buildQuery(Query $query, string $field, $value, array $data = []): Query
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
