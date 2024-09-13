## Custom Filter Types and Criteria

You can create custom filter types by extending the `AbstractFilter` class, and custom criteria by extending the `BaseCriterion` class. Here are examples of both:

### Custom Filter: RangeFilter

```php
use CakeDC\SearchFilter\Filter\AbstractFilter;

class RangeFilter extends AbstractFilter
{
    protected array $properties = [
        'type' => 'range',
    ];

    protected object|array|null $conditions = [
        self::COND_BETWEEN => 'Between',
    ];
}
```


### Custom Criterion: RangeCriterion

```php
use CakeDC\SearchFilter\Model\Filter\Criterion\BaseCriterion;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;
use CakeDC\SearchFilter\Filter\AbstractFilter;

class RangeCriterion extends BaseCriterion
{
    public function __construct(string|ExpressionInterface $field)
    {
        $this->field = $field;
    }

    public function __invoke(Query $query, string $condition, array $values, array $criteria, array $options): Query
    {
        $filter = $this->buildFilter($condition, $values, $criteria, $options);
        if (!empty($filter)) {
            return $query->where($filter);
        }

        return $query;
    }

    public function buildFilter(string $condition, array $values, array $criteria, array $options = []): array|callable|null
    {
        return function (QueryExpression $exp) use ($values) {
            if (!empty($values['from']) && !empty($values['to'])) {
                return $exp->between($this->field, $values['from'], $values['to']);
            }
            return $exp;
        };
    }

    public function isApplicable(mixed $value, string $condition): bool
    {
        return !empty($value['from']) || !empty($value['to']);
    }
}
```

## Registering and Using Custom Filters

To use your custom filter, you need to register it with the FilterRegistry. You can do this in your controller or in a custom setup method:

```php
public function initialize(): void
{
    parent::initialize();

    $manager = new Manager($this->request);
    $manager->filters()->load('range', ['className' => RangeFilter::class]);
}
```


Now you can use the custom filter and criterion in your controller action:

```php
public function index()
{
    $manager = new Manager($this->request);
    $collection = $manager->newCollection();

    $collection->add('price', $manager->filters()
        ->getNew('range')
        ->setLabel('Price Range')
        ->setCriterion(new RangeCriterion('price'))
    );

    // ... rest of your filter setup and query handling
}
```


This setup allows you to create a range filter for backend that can be used for numeric ranges, such as price ranges or date ranges, providing a more specific and efficient way to filter your data. The `filters()` method provides access to the FilterRegistry, allowing you to add custom filters.

## Vue frontend widget implementation.

Let's add the template for the RangeFilter add load it after embedding `v_templates.php` file:

```html
<script type="text/x-template" id="search-input-range-template">
  <span class="range-wrapper">
    <input
      type="number"
      class="form-control value value-from"
      :name="'v[' + index + '][from][]'"
      v-model="fromValue"
      @input="updateValue"
      :placeholder="field.fromPlaceholder || 'From'"
    />
    <span class="range-separator">-</span>
    <input
      type="number"
      class="form-control value value-to"
      :name="'v[' + index + '][to][]'"
      v-model="toValue"
      @input="updateValue"
      :placeholder="field.toPlaceholder || 'To'"
    />
  </span>
</script>
```

Now, let's add the Vue component for the RangeFilter. You can add this to your `main.js` file or a separate JavaScript file that's included in your application:

```javascript
const RangeInput = {
  template: "#search-input-range-template",
  props: ['index', 'value', 'field'],
  data() {
    return {
      fromValue: '',
      toValue: '',
    };
  },
  methods: {
    updateValue() {
      this.$emit('change-value', {
        index: this.index,
        value: {
          from: this.fromValue,
          to: this.toValue
        }
      });
    }
  },
  mounted() {
    if (this.value) {
      this.fromValue = this.value.from || '';
      this.toValue = this.value.to || '';
    }
  },
  watch: {
    value(newValue) {
      if (newValue) {
        this.fromValue = newValue.from || '';
        this.toValue = newValue.to || '';
      } else {
        this.fromValue = '';
        this.toValue = '';
      }
    }
  }
};
```

### Registering RangeInput Component

The `RangeInput` component can now be registered with the search application using the registration system.

To register the `RangeInput` component, follow these steps:

1. Use the `createMyApp` function provided by `window._search`.
2. Pass a registration function as the second argument to `createMyApp`.

#### Example Registration

```javascript
function register(app, registrator) {
    app.component('RangeInput', RangeInput);

    registrator('range', function(condition, type) {
        return 'RangeInput';
    });
}

// Create the app with the registration function
window._search.createMyApp(window._search.rootElemId, register);
```

Finally, add some CSS to style the range inputs:

```css
.range-wrapper {
  display: flex;
  align-items: center;
}

.range-separator {
  margin: 0 10px;
}

.value-from,
.value-to {
  width: 100px;
}
```

To use this new RangeFilter in your controller, you would do something like this:

```php
$collection->add('price', $manager->filters()
    ->getNew('range')
    ->setLabel('Price Range')
    ->setCriterion(new RangeCriterion('price'))
    ->setProperty('fromPlaceholder', 'Min Price')
    ->setProperty('toPlaceholder', 'Max Price')
);
```

This setup creates a new Vue component for the RangeFilter, which displays two number inputs for the "from" and "to" values. The component emits changes to its parent, allowing the search functionality to update accordingly.
