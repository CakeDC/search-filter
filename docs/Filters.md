Certainly! I'll update the examples to use object chaining calls for a more concise and fluent syntax. Here's the revised documentation with chained method calls:

# Search Filter Documentation

## Overview

Search Filters in this library provide a flexible way to define and configure various types of filters for your search functionality. Each filter type is designed for specific data types or search scenarios and is associated with a specific Vue widget for rendering.

## Index of Filters

1. [BooleanFilter](#booleanfilter)
2. [DateFilter](#datefilter)
3. [DateTimeFilter](#datetimefilter)
4. [LookupFilter](#lookupfilter)
5. [MultipleFilter](#multiplefilter)
6. [NumericFilter](#numericfilter)
7. [SelectFilter](#selectfilter)
8. [StringFilter](#stringfilter)

## Filter Types

### BooleanFilter

**Purpose:** Used for boolean-based filtering, typically for Yes/No selections.

**Vue Widget:** `SearchSelect`

**Configuration:**
```php
use CakeDC\SearchFilter\Filter\BooleanFilter;
use CakeDC\SearchFilter\Model\Filter\Criterion\BoolCriterion;

$booleanFilter = (new BooleanFilter())
    ->setCriterion(new BoolCriterion('is_active'))
    ->setLabel('Active Status')
    ->setOptions([1 => 'Active', 0 => 'Inactive']);
```


**Key Features:**
- Provides Yes/No options by default.
- Can customize options using `setOptions()` method.
- Renders as a select input.

---

### DateFilter

**Purpose:** Used for date-based filtering, supporting various date formats and conditions.

**Vue Widget:** `SearchInputDate`, `SearchInputDateRange`, or `SearchInputDateFixed` (depending on the condition)

**Configuration:**
```php
use CakeDC\SearchFilter\Filter\DateFilter;
use CakeDC\SearchFilter\Model\Filter\Criterion\DateCriterion;

$dateFilter = (new DateFilter())
    ->setCriterion(new DateCriterion('created_date'))
    ->setLabel('Creation Date')
    ->setDateFormat('YYYY-MM-DD');
```


**Key Features:**
- Default date format is 'DD/MM/YYYY'.
- Supports conditions like equals, greater than, less than, between, etc.
- Includes special conditions like 'Today', 'Yesterday', 'This week', 'Last week'.

---

### DateTimeFilter

**Purpose:** Used for datetime-based filtering, supporting both date and time components.

**Vue Widget:** `SearchInputDateTime`, `SearchInputDateTimeRange`, or `SearchInputDateTimeFixed` (depending on the condition)

**Configuration:**
```php
use CakeDC\SearchFilter\Filter\DateTimeFilter;
use CakeDC\SearchFilter\Model\Filter\Criterion\DateTimeCriterion;

$dateTimeFilter = (new DateTimeFilter())
    ->setCriterion(new DateTimeCriterion('created_at'))
    ->setLabel('Creation Date and Time')
    ->setProperty('dateFormat', 'YYYY-MM-DD HH:mm:ss');
```


**Key Features:**
- Default datetime format is 'DD/MM/YYYY hh:mm A'.
- Supports the same conditions as DateFilter, but includes time in comparisons.

---

### LookupFilter

**Purpose:** Used for autocomplete-based filtering, allowing lookup of values based on a query string.

**Vue Widget:** `SearchLookupInput` or `SearchMultiple` (for 'in' condition)

**Configuration:**
```php
use CakeDC\SearchFilter\Filter\LookupFilter;
use CakeDC\SearchFilter\Model\Filter\Criterion\LookupCriterion;
use CakeDC\SearchFilter\Model\Filter\Criterion\StringCriterion;

$lookupFilter = (new LookupFilter())
    ->setCriterion(new LookupCriterion('user_id', $usersTable, new StringCriterion('name')))
    ->setLabel('User')
    ->setLookupFields(['name', 'email'])
    ->setAutocompleteRoute(['controller' => 'Users', 'action' => 'autocomplete']);
```


**Key Features:**
- Supports autocomplete functionality.
- Configurable lookup fields and autocomplete route.
- Generates autocomplete URL automatically.

---

### MultipleFilter

**Purpose:** Used for filtering based on multiple selected values.

**Vue Widget:** `SearchMultiple`

**Configuration:**
```php
use CakeDC\SearchFilter\Filter\MultipleFilter;
use CakeDC\SearchFilter\Model\Filter\Criterion\InCriterion;
use CakeDC\SearchFilter\Model\Filter\Criterion\StringCriterion;

$multipleFilter = (new MultipleFilter())
    ->setCriterion(new InCriterion('category_id', $categoriesTable, new StringCriterion('name')))
    ->setLabel('Categories')
    ->setProperty('placeholder', 'Select multiple options');
```


**Key Features:**
- Supports 'In' and 'Not In' conditions.
- Designed for multiple selections.

---

### NumericFilter

**Purpose:** Used for numeric-based filtering, supporting various numeric comparisons.

**Vue Widget:** `SearchInput` or `SearchInputNumericRange` (for 'between' condition)

**Configuration:**
```php
use CakeDC\SearchFilter\Filter\NumericFilter;
use CakeDC\SearchFilter\Model\Filter\Criterion\NumericCriterion;

$numericFilter = (new NumericFilter())
    ->setCriterion(new NumericCriterion('price'))
    ->setLabel('Price')
    ->setProperty('step', '0.01'); // For decimal numbers
```


**Key Features:**
- Supports conditions like equals, not equals, greater than, less than, between, etc.
- Specifically designed for numeric values.

---

### SelectFilter

**Purpose:** Used for select-based filtering, allowing selection from predefined options.

**Vue Widget:** `SearchSelect` or `SearchMultiple` (for 'in' condition)

**Configuration:**
```php
use CakeDC\SearchFilter\Filter\SelectFilter;
use CakeDC\SearchFilter\Model\Filter\Criterion\InCriterion;
use CakeDC\SearchFilter\Model\Filter\Criterion\StringCriterion;

$selectFilter = (new SelectFilter())
    ->setCriterion(new InCriterion('status', $statusesTable, new StringCriterion('name')))
    ->setLabel('Status')
    ->setOptions(['active' => 'Active', 'inactive' => 'Inactive'])
    ->setEmpty('All Statuses');
```


**Key Features:**
- Supports custom options.
- Can set an empty option.
- Supports conditions like equals, not equals, in, like.

---

### StringFilter

**Purpose:** Used for string-based filtering, supporting various string comparison conditions.

**Vue Widget:** `SearchInput` or `SearchMultiple` (for 'in' condition)

**Configuration:**
```php
use CakeDC\SearchFilter\Filter\StringFilter;
use CakeDC\SearchFilter\Model\Filter\Criterion\StringCriterion;

$stringFilter = (new StringFilter())
    ->setCriterion(new StringCriterion('title'))
    ->setLabel('Title')
    ->setType('email');
```


**Key Features:**
- Supports conditions like like, equals, not equals, in.
- Can be configured for different input types (e.g., text, email).

---

These filters can be used to create a comprehensive search functionality in your application. They provide a range of options for different data types and search requirements, allowing for flexible and powerful search implementations. The associated Vue widgets ensure that the appropriate input type is rendered based on the filter type and condition.