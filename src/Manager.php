<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter;

use Cake\Http\ServerRequest;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use CakeDC\SearchFilter\Filter\AbstractFilter;
use CakeDC\SearchFilter\Filter\BooleanFilter;
use CakeDC\SearchFilter\Filter\DateFilter;
use CakeDC\SearchFilter\Filter\DateTimeFilter;
use CakeDC\SearchFilter\Filter\FilterCollection;
use CakeDC\SearchFilter\Filter\FilterInterface;
use CakeDC\SearchFilter\Filter\FilterRegistry;
use CakeDC\SearchFilter\Filter\LookupFilter;
use CakeDC\SearchFilter\Filter\MultipleFilter;
use CakeDC\SearchFilter\Filter\NumericFilter;
use CakeDC\SearchFilter\Filter\SelectFilter;
use CakeDC\SearchFilter\Filter\StringFilter;
use CakeDC\SearchFilter\Model\Filter\Criterion\BaseCriterion;
use CakeDC\SearchFilter\Model\Filter\Criterion\CriteriaBuilder;

/**
 * Manager class
 *
 * This class manages the search filter functionality, including filter creation,
 * configuration, and application to queries.
 */
class Manager
{
    /**
     * Filters collection
     *
     * @var \CakeDC\SearchFilter\Filter\FilterRegistry|null
     */
    protected ?FilterRegistry $_filters = null;

    /**
     * List of fields to be excluded from filtering
     *
     * @var array<string>
     */
    protected array $fieldBlacklist = ['id', 'password', 'created', 'modified'];

    /**
     * List of fields to be excluded from filtering
     *
     * @var array<string>
     */
    protected array $defaultLookupFields = ['name', 'title', 'id'];

    /**
     * The current server request
     *
     * @var \Cake\Http\ServerRequest
     */
    protected ServerRequest $request;

    /**
     * Instance of a criteria builder object that can be used for
     * generating complex criteria
     *
     * @var \CakeDC\SearchFilter\Model\Filter\Criterion\CriteriaBuilder
     */
    protected CriteriaBuilder $_criteriaBuilder;

    /**
     * A configuration array for filters to be loaded.
     *
     * @var array<string, array<string, mixed>>
     */
    protected array $filters = [
        'boolean' => ['className' => BooleanFilter::class],
        'date' => ['className' => DateFilter::class],
        'datetime' => ['className' => DateTimeFilter::class],
        'lookup' => ['className' => LookupFilter::class],
        'multiple' => ['className' => MultipleFilter::class],
        'numeric' => ['className' => NumericFilter::class],
        'select' => ['className' => SelectFilter::class],
        'string' => ['className' => StringFilter::class],
    ];

    /**
     * Constructor
     *
     * @param \Cake\Http\ServerRequest $request The server request
     * @param array<string, array<string, mixed>>|null $filters Optional filter configurations to override defaults
     */
    public function __construct(ServerRequest $request, ?array $filters = null)
    {
        $this->request = $request;
        if (!empty($filters)) {
            $this->filters = $filters;
        }

        $this->loadFilters();
        $this->_criteriaBuilder = new CriteriaBuilder();
    }

    /**
     * Get the filter registry in use by this class.
     *
     * @return \CakeDC\SearchFilter\Filter\FilterRegistry
     */
    public function filters(): FilterRegistry
    {
        return $this->_filters ??= new FilterRegistry();
    }

    /**
     * Returns an instance of criteria builder object that can be used for
     * generating complex criteria.
     *
     * ### Example:
     *
     * ```
     * $manager->criterion()->or([...]);
     * $manager->criterion()->in(...);
     * ```
     *
     * @return \CakeDC\SearchFilter\Model\Filter\Criterion\CriteriaBuilder
     */
    public function criterion(): CriteriaBuilder
    {
        return $this->_criteriaBuilder;
    }

    /**
     * Interact with the FilterRegistry to load all the helpers.
     *
     * @return $this
     */
    public function loadFilters()
    {
        foreach ($this->filters as $name => $config) {
            $this->loadFilter($name, $config);
        }

        return $this;
    }

    /**
     * Load a specific filter
     *
     * @param string $name The name of the filter to load
     * @param array<string, mixed> $config Configuration for the filter
     * @return \CakeDC\SearchFilter\Filter\FilterInterface The loaded filter
     */
    public function loadFilter(string $name, array $config = []): FilterInterface
    {
        /** @var \CakeDC\SearchFilter\Filter\FilterInterface */
        return $this->filters()->load($name, $config);
    }

    /**
     * Get the field blacklist
     *
     * @return array<string>
     */
    public function getFieldBlacklist(): array
    {
        return $this->fieldBlacklist;
    }

    /**
     * Set the field blacklist
     *
     * @param array<string> $fields The fields to blacklist
     * @return self
     */
    public function setFieldBlacklist(array $fields): self
    {
        $this->fieldBlacklist = $fields;

        return $this;
    }

    /**
     * Get the default lookup fields
     *
     * @return array<string>
     */
    public function getDefaultLookupFields(): array
    {
        return $this->defaultLookupFields;
    }

    /**
     * Set the default lookup fields
     *
     * @param array<string> $defaultLookupFields The lookup fields to set
     * @return self
     */
    public function setDefaultLookupFields(array $defaultLookupFields): self
    {
        $this->defaultLookupFields = $defaultLookupFields;

        return $this;
    }

    /**
     * Create a new FilterCollection instance
     *
     * @return \CakeDC\SearchFilter\Filter\FilterCollection
     */
    public function newCollection(): FilterCollection
    {
        return new FilterCollection();
    }

    /**
     * Append filters from table schema to the collection
     *
     * @param \CakeDC\SearchFilter\Filter\FilterCollection $collection The filter collection
     * @param \Cake\ORM\Table $table The table to get schema from
     * @param array<string, string> $labels Custom labels for fields
     * @param array<string>|null $skipFields Fields to skip
     * @param array<string, mixed> $options Additional options
     * @return \CakeDC\SearchFilter\Filter\FilterCollection
     */
    public function appendFromSchema(FilterCollection $collection, Table $table, array $labels = [], ?array $skipFields = null, ?array $options = []): FilterCollection
    {
        if ($skipFields === null) {
            $skipFields = $this->getFieldBlacklist();
        }
        $schema = $table->getSchema();
        foreach ($schema->columns() as $column) {
            if (in_array($column, $skipFields) || $collection->has($column)) {
                continue;
            }
            $type = $schema->getColumnType($column);
            if ($type === null) {
                continue;
            }
            $filter = $this->buildFilter($column, $type, $table, $labels);
            if ($filter === null) {
                continue;
            }

            $criterion = $this->buildCriterion($column, $type, $table, $options ?? []);
            if ($criterion !== null) {
                $filter->setCriterion($criterion);
            }
            $collection->add($column, $filter);
        }

        return $collection;
    }

    /**
     * Build a criterion based on column type
     *
     * @param string $column The column name
     * @param string $type The column type
     * @param \Cake\ORM\Table $table The table instance
     * @param array<string, mixed> $options Additional options
     * @return \CakeDC\SearchFilter\Model\Filter\Criterion\BaseCriterion|null
     */
    public function buildCriterion(string $column, string $type, Table $table, array $options = []): ?BaseCriterion
    {
        $alias = $table->getAlias();
        if (in_array($type, ['string', 'text'])) {
            return $this->criterion()->string($alias . '.' . $column);
        } elseif (in_array($type, ['date'])) {
            return $this->criterion()->date($alias . '.' . $column);
        } elseif (in_array($type, ['time', 'timestamp', 'datetime'])) {
            return $this->criterion()->datetime($alias . '.' . $column);
        } elseif (($type === 'integer' || $type == 'uuid') && (substr($column, -3) === '_id')) {
            $assocName = Inflector::pluralize(Inflector::camelize(substr($column, 0, -3)));
            if ($table->associations()->has($assocName)) {
                $assoc = $table->associations()->get($assocName);
                if ($assoc !== null) {
                    $assocSchema = $assoc->getTarget()->getSchema();

                    $fields = $options['defaultLookupFields'] ?? $this->getDefaultLookupFields();
                    foreach ($fields as $field) {
                        if ($assocSchema->getColumn($field) !== null) {
                            return $this->criterion()->lookup($alias . '.' . $column, $assoc->getTarget(), $this->criterion()->string($field));
                        }
                    }
                }
            }
        } elseif (in_array($type, ['integer', 'float', 'decimal', 'biginteger'])) {
            return $this->criterion()->numeric($alias . '.' . $column);
        } elseif ($type === 'boolean') {
            return $this->criterion()->bool($alias . '.' . $column);
        }

        return null;
    }

    /**
     * Build a filter based on column type
     *
     * @param string $column The column name
     * @param string $type The column type
     * @param \Cake\ORM\Table $table The table instance
     * @param array<string, string> $labels Custom labels for fields
     * @return \CakeDC\SearchFilter\Filter\FilterInterface|null
     */
    protected function buildFilter(string $column, string $type, Table $table, array $labels): ?FilterInterface
    {
        $filter = null;
        if (in_array($type, ['string', 'text'])) {
            $filter = $this->filters()->new('string');
        } elseif (in_array($type, ['date'])) {
            $filter = $this->filters()->new('date');
        } elseif (in_array($type, ['time', 'timestamp', 'datetime'])) {
            $filter = $this->filters()->new('datetime');
        } elseif (($type === 'integer' || $type == 'uuid') && (substr($column, -3) === '_id')) {
            $assocName = Inflector::pluralize(Inflector::camelize(substr($column, 0, -3)));

            /** @var \CakeDC\SearchFilter\Filter\LookupFilter $lookupFilter */
            $lookupFilter = $this->filters()->new('lookup');
            if ($table->associations()->has($assocName)) {
                $assoc = $table->associations()->get($assocName);
                if ($assoc !== null) {
                    $assocSchema = $assoc->getTarget()->getSchema();

                    $fields = $lookupFilter->getLookupFields();
                    foreach ($fields as $field) {
                        if ($assocSchema->getColumn($field) !== null) {
                            $filter = $lookupFilter;
                            $filter->setProperty('valueName', $field);
                            $filter->setProperty('query', $field . '=%QUERY');
                            $url = $lookupFilter->getAutocompleteRoute();
                            $url['controller'] = $assocName;
                            $lookupFilter->setAutocompleteRoute($url);
                            $fieldName = substr($column, 0, -3) . '_' . $field;

                            if (array_key_exists($fieldName, $labels)) {
                                $filter->setLabel($labels[$fieldName]);
                            } else {
                                $filter->setLabel(Inflector::humanize($fieldName));
                            }

                            return $filter;
                        }
                    }
                }
            }
        } elseif (in_array($type, ['integer', 'float', 'decimal', 'biginteger'])) {
            $filter = $this->filters()->new('numeric');
        } elseif ($type === 'boolean') {
            $filter = $this->filters()->new('boolean');
        }

        if ($filter !== null) {
            if (array_key_exists($column, $labels)) {
                $filter->setLabel($labels[$column]);
            } else {
                $filter->setLabel(Inflector::humanize($column));
            }
        }

        return $filter;
    }

    /**
     * Format search data from query parameters
     *
     * @param array<string, mixed>|null $queryParams Query parameters
     * @return array<string, mixed>
     */
    public function formatSearchData(?array $queryParams = null): array
    {
        if ($queryParams === null) {
            $queryParams = $this->request->getQuery();
        }
        $search = [];
        $fields = Hash::get($queryParams, 'f', []);
        $conditions = Hash::get($queryParams, 'c', []);
        $values = Hash::get($queryParams, 'v', []);

        foreach ($fields as $fieldId => $field) {
            $value = Hash::get($values, $fieldId, []);
            $newVal = [];
            foreach ($value as $k => $v) {
                if (is_array($v) && count($v) == 1) {
                    $val = array_shift($v);
                    $newVal[$k] = $val;
                } elseif (is_array($v) && count($v) > 1) {
                    foreach ($v as $i => $val) {
                        $newVal[$i][$k] = $val;
                    }
                }
            }
            $condition = Hash::get($conditions, $fieldId);
            if ($condition == 'null') {
                $condition = null;
            }
            if (
                in_array($condition, [AbstractFilter::COND_IN, AbstractFilter::COND_NOT_IN])
                && !Hash::numeric(array_keys($newVal))
            ) {
                $newVal = [$newVal];
            }
            $search[$field] = [
                'condition' => $condition,
                'value' => $newVal,
            ];
        }

        return $search;
    }

    /**
     * Format finders from search data
     *
     * @param array<string, mixed> $search The search data
     * @return array<string, mixed>
     */
    public function formatFinders(array $search): array
    {
        $filters = [];
        if (isset($search['search']) && Hash::get($search['search'], 'value.value') != null) {
            $filters['search'] = Hash::get($search['search'], 'value.value');
            unset($search['search']);
        }
        if (!empty($search)) {
            $filters['multiple'] = $search;
        }

        return $filters;
    }
}
