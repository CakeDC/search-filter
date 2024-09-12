<?php
declare(strict_types=1);

/**
 * Copyright 2019 Cake Development Corporation, Las Vegas, Nevada (702) 425-5085 www.cakedc.com use and restrictions are governed by Section 8.5 of The Professional Services Agreement.
 * Redistribution is prohibited. All Rights Reserved.
 */

namespace CakeDC\SearchFilter\Filter;

use Cake\Core\App;
use Cake\Core\ObjectRegistry;
use CakeDC\SearchFilter\Filter\Exception\MissingFilterException;

/**
 * @extends \Cake\Core\ObjectRegistry<\CakeDC\SearchFilter\Filter\FilterInterface>
 */
class FilterRegistry extends ObjectRegistry
{
    /**
     * Tries to lazy load a filter based on its name
     *
     * @param string $filter The filter name to be loaded
     * @return bool whether the filter could be loaded or not
     */
    public function __isset(string $filter): bool
    {
        if (isset($this->_loaded[$filter])) {
            return true;
        }

        $this->load($filter);

        return true;
    }

    /**
     * Provide public read access to the loaded objects
     *
     * @param string $name Name of property to read
     * @return \CakeDC\SearchFilter\Filter\FilterInterface|null
     */
    public function __get(string $name): ?FilterInterface
    {
        if (isset($this->{$name})) {
            return $this->_loaded[$name];
        }

        return null;
    }

    /**
     * Resolve a filter classname.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string $class Partial classname to resolve.
     * @return class-string<\CakeDC\SearchFilter\Filter\FilterInterface>|null Either the correct class name or null.
     */
    protected function _resolveClassName(string $class): ?string
    {
        /** @var class-string<\CakeDC\SearchFilter\Filter\FilterInterface>|null */
        return App::className($class, 'Filter', 'Filter');
    }

    /**
     * Throws an exception when a filter is missing.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     * and Cake\Core\ObjectRegistry::unload()
     *
     * @param string $class The classname that is missing.
     * @param string|null $plugin The plugin the filter is missing in.
     * @return void
     * @throws \CakeDC\SearchFilter\Filter\Exception\MissingFilterException
     */
    protected function _throwMissingClassError(string $class, ?string $plugin): void
    {
        throw new MissingFilterException([
            'class' => $class . 'Filter',
            'plugin' => $plugin,
        ]);
    }

    /**
     * Get copy of filter instance.
     *
     * @param string $name Name of object.
     * @return \CakeDC\SearchFilter\Filter\FilterInterface Object instance.
     */
    public function new(string $name): FilterInterface
    {
        return $this->get($name)->new();
    }

    /**
     * Create the filter instance.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     * Enabled filters will be registered with the event manager.
     *
     * @param \CakeDC\SearchFilter\Filter\FilterInterface|class-string<\CakeDC\SearchFilter\Filter\FilterInterface> $class The class to create.
     * @param string $alias The alias of the loaded filter.
     * @param array<string, mixed> $config An array of settings to use for the filter.
     * @return \CakeDC\SearchFilter\Filter\FilterInterface The constructed filter class.
     */
    protected function _create($class, string $alias, array $config): FilterInterface
    {
        if (is_object($class)) {
            return $class;
        }

        return new $class($config);
    }
}
