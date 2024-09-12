# Search Filter Criteria Documentation

## Overview

Search Filter Criteria allow you to build complex, flexible search queries in your CakePHP application. Each criterion type serves a specific purpose and can be combined to create powerful search functionality.

## Index of Criteria

1. [AndCriterion](#andcriterion)
2. [BoolCriterion](#boolcriterion)
3. [DateCriterion](#datecriterion)
4. [DateTimeCriterion](#datetimecriterion)
5. [InCriterion](#incriterion)
6. [LookupCriterion](#lookupcriterion)
7. [NumericCriterion](#numericcriterion)
8. [OrCriterion](#orcriterion)
9. [StringCriterion](#stringcriterion)

## Criterion Types

### AndCriterion

**Purpose:** Combines multiple criteria with AND logic.

**Configuration:**
```php
use CakeDC\SearchFilter\Model\Filter\Criterion\AndCriterion;

$andCriterion = new AndCriterion($criteria);
```


**Parameters:**
- `$criteria` (array): An array of BaseCriterion objects to be combined with AND logic.

**Example:**
```php
$andCriterion = new AndCriterion([
    new DateCriterion('created'),
    new BoolCriterion('is_active')
]);
```


This will create a condition that matches both the creation date AND the active status.

---

### BoolCriterion

**Purpose:** Filters results based on a boolean field.

**Configuration:**
```php
use CakeDC\SearchFilter\Model\Filter\Criterion\BoolCriterion;

$boolCriterion = new BoolCriterion($field);
```


**Parameters:**
- `$field` (string|\Cake\Database\ExpressionInterface): The boolean field to filter on.

**Example:**
```php
$boolCriterion = new BoolCriterion('is_published');
```


This will filter results based on whether the 'is_published' field is true or false.

---

### DateCriterion

**Purpose:** Filters results based on date fields, supporting various comparison types.

**Configuration:**
```php
use CakeDC\SearchFilter\Model\Filter\Criterion\DateCriterion;

$dateCriterion = new DateCriterion($field, $format = 'Y-m-d');
```


**Parameters:**
- `$field` (string|\Cake\Database\ExpressionInterface): The date field to filter on.
- `$format` (string): The date format string (default: 'Y-m-d').

**Example:**
```php
$dateCriterion = new DateCriterion('created_date', 'Y-m-d');
```


This allows filtering on date fields with support for various conditions like 'between', 'greater than', etc.

---

### DateTimeCriterion

**Purpose:** Filters results based on datetime fields.

**Configuration:**
```php
use CakeDC\SearchFilter\Model\Filter\Criterion\DateTimeCriterion;

$dateTimeCriterion = new DateTimeCriterion($field, $format = 'Y-m-d\TH:i');
```


**Parameters:**
- `$field` (string|\Cake\Database\ExpressionInterface): The datetime field to filter on.
- `$format` (string): The datetime format string (default: 'Y-m-d\TH:i').

**Example:**
```php
$dateTimeCriterion = new DateTimeCriterion('created_at', 'Y-m-d H:i:s');
```


This allows filtering on datetime fields with support for various conditions, similar to DateCriterion but including time.

---

### InCriterion

**Purpose:** Filters results where a field's value is in a set of values determined by a subquery.

**Configuration:**
```php
use CakeDC\SearchFilter\Model\Filter\Criterion\InCriterion;

$inCriterion = new InCriterion($field, $table, $criterion);
```


**Parameters:**
- `$field` (string|\Cake\Database\ExpressionInterface): The field to filter on.
- `$table` (\Cake\ORM\Table): The table used for the subquery.
- `$criterion` (BaseCriterion): The criterion used to build the subquery.

**Example:**
```php
$authorTable = $this->getTableLocator()->get('Authors');
$stringCriterion = new StringCriterion('name');
$inCriterion = new InCriterion('author_id', $authorTable, $stringCriterion);
```


This will create a subquery to find authors and then filter the main query based on the results.

---

### LookupCriterion

**Purpose:** Performs a lookup in a related table based on a search term.

**Configuration:**
```php
use CakeDC\SearchFilter\Model\Filter\Criterion\LookupCriterion;

$lookupCriterion = new LookupCriterion($field, $table, $criterion);
```


**Parameters:**
- `$field` (string|\Cake\Database\ExpressionInterface): The field to filter on.
- `$table` (\Cake\ORM\Table): The related table to perform the lookup on.
- `$criterion` (BaseCriterion): The criterion used for the lookup.

**Example:**
```php
$authorTable = $this->getTableLocator()->get('Authors');
$stringCriterion = new StringCriterion('name');
$lookupCriterion = new LookupCriterion('author_id', $authorTable, $stringCriterion);
```


This allows searching in related tables and filtering the main query based on the results.

---

### NumericCriterion

**Purpose:** Filters results based on numeric fields.

**Configuration:**
```php
use CakeDC\SearchFilter\Model\Filter\Criterion\NumericCriterion;

$numericCriterion = new NumericCriterion($field);
```


**Parameters:**
- `$field` (string|\Cake\Database\ExpressionInterface): The numeric field to filter on.

**Example:**
```php
$numericCriterion = new NumericCriterion('price');
```


This allows filtering on numeric fields with support for various conditions like 'greater than', 'between', etc.

---

### OrCriterion

**Purpose:** Combines multiple criteria with OR logic.

**Configuration:**
```php
use CakeDC\SearchFilter\Model\Filter\Criterion\OrCriterion;

$orCriterion = new OrCriterion($criteria);
```


**Parameters:**
- `$criteria` (array): An array of CriterionInterface objects to be combined with OR logic.

**Example:**
```php
$orCriterion = new OrCriterion([
    new StringCriterion('title'),
    new StringCriterion('content')
]);
```


This will create a condition that matches either the title OR the content.

---

### StringCriterion

**Purpose:** Filters results based on string matching.

**Configuration:**
```php
use CakeDC\SearchFilter\Model\Filter\Criterion\StringCriterion;

$stringCriterion = new StringCriterion($field);
```


**Parameters:**
- `$field` (string|\Cake\Database\ExpressionInterface): The string field to filter on.

**Example:**
```php
$stringCriterion = new StringCriterion('title');
```


This allows filtering on string fields with support for various conditions like 'contains', 'starts with', etc.

---

These criteria can be combined to create complex search queries. For example, you can use AndCriterion or OrCriterion to group multiple criteria together.