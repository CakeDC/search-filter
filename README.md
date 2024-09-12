CakeDC SearchFilter Plugin for CakePHP
===================

[![Build Status](https://img.shields.io/github/actions/workflow/status/CakeDC/search-filter/ci.yml?branch=main&style=flat-square)](https://github.com/CakeDC/search-filter/actions?query=workflow%3ACI+branch%3Amain)
[![Coverage Status](https://img.shields.io/codecov/c/gh/CakeDC/search-filter.svg?style=flat-square)](https://codecov.io/gh/CakeDC/search-filter)
[![Downloads](https://poser.pugx.org/CakeDC/search-filter/d/total.png)](https://packagist.org/packages/CakeDC/search-filter)
[![License](https://poser.pugx.org/CakeDC/search-filter/license.svg)](https://packagist.org/packages/CakeDC/search-filter)

Versions and branches
---------------------

| CakePHP | CakeDC Users Plugin | Tag   | Notes |
| :-------------: | :------------------------: | :--:  | :---- |
| ^5.0            | [2.0](https://github.com/cakedc/users/tree/2.next-cake5)                      | 2.0.0 | stable |
| ^4.5            | [1.0](https://github.com/cakedc/search-filter/tree/1.next-cake4)              | 1.0.0 | stable |

## Overview

The SearchFilter plugin is a powerful and flexible solution for implementing advanced search functionality in CakePHP applications. It provides a robust set of tools for creating dynamic, user-friendly search interfaces with minimal effort.

## Features

- Dynamic filter generation based on database schema
- Support for various filter types: string, numeric, date, datetime, boolean, and lookup (autocomplete)
- Customizable filter conditions (equals, not equals, greater than, less than, between, etc.)
- Vue.js based frontend for an interactive user experience
- AJAX-powered autocomplete functionality for lookup filters
- Easy integration with CakePHP's ORM for efficient query building
- Extensible architecture allowing for custom filter types and conditions

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org):

```
composer require cakedc/search-filter
```

Then, add the following line to your application's `src/Application.php` file:

```php
$this->addPlugin('CakeDC.SearchFilter');
```

## Configuration

* [Criteria List](docs/Criteria.md)
* [Filters List](docs/Filters.md)

## Basic Usage

### Controller

In your controller, you can set up the search functionality like this:

```php
use CakeDC\SearchFilter\Manager;

class PostsController extends AppController
{
    public function index()
    {
        $query = $this->Posts->find();

        $manager = new Manager($this->request);
        $collection = $manager->newCollection();

        // Add a general search filter
        $collection->add('search', $manager->filters()
            ->new('string')
            ->setConditions(new \stdClass())
            ->setLabel('Search...')
        );

        // Add a complex name filter that searches across multiple fields
        $collection->add('name', $manager->filters()
            ->new('string')
            ->setLabel('Name')
            ->setCriterion(
                $manager->criterion()->or([
                    $manager->buildCriterion('title', 'string', $this->Posts),
                    $manager->buildCriterion('body', 'string', $this->Posts),
                    $manager->buildCriterion('author', 'string', $this->Posts),
                ])
            )
        );

        // Add a datetime filter for the 'created' field
        $collection->add('created', $manager->filters()
            ->new('datetime')
            ->setLabel('Created')
            ->setCriterion($manager->buildCriterion('created', 'datetime', $this->Posts))
        );

        // Automatically add filters based on the table schema
        $manager->appendFromSchema($collection, $this->Posts);

        // Get the view configuration for the filters
        $viewFields = $collection->getViewConfig();
        $this->set('viewFields', $viewFields);

        // Apply filters if search parameters are present in the request
        if (!empty($this->getRequest()->getQuery()) && !empty($this->getRequest()->getQuery('f'))) {
            $search = $manager->formatSearchData();
            $this->set('values', $search);

            // Add a custom 'multiple' filter using the CriteriaFilter
            $this->Posts->addFilter('multiple', [
                'className' => 'CakeDC/SearchFilter.Criteria',
                'criteria' => $collection->getCriteria(),
            ]);

            $filters = $manager->formatFinders($search);
            $query = $query->find('filters', $filters);
        }

        // Paginate the results
        $posts = $this->paginate($this->Filter->prg($query));
        $this->set(compact('posts'));
    }
}
```

This example demonstrates several key features of the SearchFilter plugin:

1. Creating a new `Manager` instance and filter collection.
2. Adding a general search filter that can be used for quick searches.
3. Creating a complex filter that searches across multiple fields using `OrCriterion`.
4. Adding a datetime filter for a specific field.
5. Automatically generating filters based on the table schema.
6. Applying filters when search parameters are present in the request.
7. Using the `CriteriaFilter` for handling multiple filter criteria.

### View

In your view, you can render the search component inside search form like this:

```php
<?= $this->element('CakeDC/SearchFilter.Search/v_search'); ?>
```

```html
<script>
    window._search.createMyApp(window._search.rootElemId)
</script>
```

## Advanced Usage

### Custom Filter Types

[Custom Range Filter implementation and integration](docs/CustomFilter.md)

## Frontend Customization

The plugin uses Vue.js for the frontend. You can customize the look and feel by overriding the templates in your application:

1. Copy the `templates/element/Search/v_templates.php` file from the plugin to your application's `templates/element/Search/` directory.
2. Modify the templates as needed.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This plugin is licensed under the [MIT License](LICENSE).
