const {
  onMounted,
  onUpdated,
  onUnmounted,
  ref,
  watch,
  reactive,
} = Vue;

function uuid() {
  const b = crypto.getRandomValues(new Uint16Array(8));
  const d = [].map.call(b, a => a.toString(16).padStart(4, '0')).join('');
  const vr = (((b[5] >> 12) & 3) | 8).toString(16);
  return `${d.substr(0, 8)}-${d.substr(8, 4)}-4${d.substr(13, 3)}-${vr}${d.substr(17, 3)}-${d.substr(20, 12)}`;
};

const isMultiple = function (condition) {
    return (condition == 'in' || condition == 'notIn');
};

const inputTypeMap = {
    'none': function (type, condition) {
        if (isMultiple(condition)) {
            return 'SearchMultiple';
        }
        return 'SearchNone';
    },
    'string': function (type, condition) {
        if (isMultiple(condition)) {
            return 'SearchMultiple';
        }
        return 'SearchInput';
    },
    'numeric': function (type, condition) {
        if (isMultiple(condition)) {
            return 'SearchMultiple';
        } else if (condition == 'between') {
            return 'SearchInputNumericRange';
        }

        return 'SearchInput';
    },
    'date': function (type, condition) {
        if (isMultiple(condition)) {
            return 'SearchMultiple';
        }
        switch (condition) {
            case 'last_week':
            case 'this_week':
            case 'yesterday':
            case 'today':
                return 'SearchInputDateFixed';
            case 'between':
                return 'SearchInputDateRange';
            default:
                return 'SearchInputDate';
        }
    },
    'datetime': function (type, condition) {
        if (isMultiple(condition)) {
            return 'SearchMultiple';
        }
        switch (condition) {
            case 'last_week':
            case 'this_week':
            case 'yesterday':
            case 'today':
                return 'SearchInputDateTimeFixed';
            case 'between':
                return 'SearchInputDateTimeRange';
            default:
                return 'SearchInputDateTime';
        }
    },
    'select': function (type, condition) {
        if (isMultiple(condition)) {
            return 'SearchSelectMultiple';
        }
        return 'SearchSelect';
    },
    'autocomplete': function (type, condition) {
        if (isMultiple(condition)) {
            return 'SearchMultiple';
        } else if (condition == 'like') {
            return 'SearchInput';
        } else  {
            return 'SearchLookupInput';
        }
    },
    'multiple': function (type, condition) {
        return 'SearchSelectMultiple';
    },
};

function registerConditions(type, checker) {
    inputTypeMap[type] = checker;
}

function getInputType(type, condition) {
    let defaultComponent = 'SearchInput';
    if (inputTypeMap.hasOwnProperty(type)) {
        let component = inputTypeMap[type](type, condition);
        if (component !== null) {
            return component;
        }
    }
    return defaultComponent;
}

const SearchApp = {
    template: "#search-list",
    rootElemId: 'ext-search',
    data() {
        return {
            filters: [],
            components: [],
            appKey: uuid(),
            shouldTeleport: false,
            teleportTarget: null,
        };
    },
    methods: {
        getProps(type, index) {
            let idx = this.components.findIndex(el => el.index == index);
            return {
                fields: this.fields,
                values: this.values,
                ...this.components[idx]
            }
        },
        emptyFilters() {
            return this.components.length == 0;
        },
        add: function(options) {
            options = options || {};
            this.components.push({
                type: 'SearchItem',
                index: this.components.length,
                key: uuid(),
                ...options
            });
        },
        addOrHighlight(filter) {
            let idx = this.components.findIndex(el => el.filter == filter);
            this.components = this.components.map((c) => {
               return {...c, highlight: false};
            })
            if (idx < 0) {
                this.add({filter:filter, highlight: true});
            } else {
                this.components = this.components.map((c) => {
                    return {...c, highlight: (c.filter == filter), key: uuid()};
                })
            }
        },

        removeAll: function() {
            this.components = []
        },
        removeItem(index) {
            let idx = this.components.findIndex(el => el.index == index);
            this.components = this.components.filter(el => el.index != index)
            this.updateComponentsIndex();
        },
        updateComponentsIndex() {
            const keys = this.components.keys();
            for (let x of keys) {
              this.components[x].index = x;
            }
        },
        selectFilter(evt) {
            let idx = this.components.findIndex(el => el.index == evt.index);
            this.components[idx] = {...this.components[idx], filter: evt.filter};
        },
        addFilter(evt) {
            this.add({ filter: evt.filter });
        },
        selectCondition(evt) {
            let idx = this.components.findIndex(el => el.index == evt.index);
            this.components[idx] = {...this.components[idx], condition: evt.condition};
        },
        setValue(evt) {
            let idx = this.components.findIndex(el => el.index == evt.index);
            this.components[idx] = {...this.components[idx], value: evt.value};
        },
        setTeleportStatus() {
            const headerId = window._search.searchHeaderId;
            if (headerId && document.getElementById(headerId)) {
                this.shouldTeleport = true;
                this.teleportTarget = `#${headerId}`;
            } else {
                this.shouldTeleport = false;
                this.teleportTarget = null;
            }
        },
    },
    mounted() {
        console.info("Search mounted!");
        this.setTeleportStatus();
        if (this.values != null && this.values != undefined) {
            const keys = Object.keys(this.values);
            for (let filter of keys) {
                this.add({filter: filter, ...this.values[filter]});
            }
            if (keys.length == 0) {
                this.add();
            }
        }
        const appRoot = document.getElementById(window._search.rootElemId);
        appRoot.addEventListener(
          "activate-filter",
          (e) => {
            this.addOrHighlight(e.detail.filter);
            // e.target matches elem
            // trigger
                // const event = new CustomEvent("activate-filter", {detail: { filter: 'title' }});
                // const el = document.getElementById(window._search.rootElemId)
                // el.dispatchEvent(event)
          },
          false,
        );
    },
    setup(props, context) {
        let fields = reactive(window._search.fields);
        let values = reactive(window._search.values);

        return {fields, values};
    }
};

const AddNewFilter = {
    template: "#search-add-filter-template",
    props: ['fields', 'components'],
    data() {
        return {
            search_filter: '',
        };
    },
    methods: {
        disabled(filter) {
            return this.components.findIndex(el => el.filter == filter) >= 0;
        },
        selectFilter(event) {
            this.$emit('add-filter', {filter: this.search_filter});
        },
    },

};

const SearchItem = {
    template: "#search-item-template",
    props: ['fields', 'index', 'filter', 'condition', 'value', 'highlight'],
    data() {
        return {
            search_filter: this.filter,
            itemClasses: ['search-filter-item'],
        };
    },
    methods: {
        selectCondition(event) {
            this.$emit('select-condition', event);
        },
        setValue(event) {
            this.$emit('change-value', event);
        },
        selectFilter(event) {
            this.$emit('select-filter', {filter: this.search_filter, index: this.index});
        },
        displayCondition() {
            return this.search_filter != '';
        },
        currentField() {
            let field = this.fields[this.search_filter];
            let localField = {index: this.index};
            return {...field, ...localField, condition: this.condition, value: this.value, field: field};
        },
        closeItem(index) {
            this.$emit('close-item', this.index)
        },
    },
    mounted() {
        if (this.highlight) {
            this.itemClasses = ['item-alert', 'search-filter-item'];
            window.scrollTo({
                top: document.getElementById("filter-" + this.filter).offsetTop,
                left: 0,
                behavior: "smooth",
            });
            setTimeout(() => { this.itemClasses = ['search-filter-item']; }, 5000);
        }
    },
    setup(props, context) {
        let fields = props.fields;

        return { fields };
    }
};

const SearchCondition = {
    template: "#search-conditions-template",
    props: ['conditions', 'name', 'type', 'index', 'condition', 'value', 'field'],
    data() {
        let condition = this.condition;
        if (condition == '' || condition == null) {
            let keys = Object.keys(this.conditions);
            if (keys.length > 0) {
                condition = keys[0];
            }
        }
        return {
            conditionValue: condition,
            inputType: getInputType(this.type, condition),
        };
    },
    methods: {
        showConditionClasses() {
            let keys = Object.keys(this.conditions)
            if (keys.length > 0) {
                return [];
            } else {
                return ['hidden'];
            }
        },
        changeCondition(event) {
            this.$emit('select-condition', {condition: this.conditionValue, index: this.index});
            this.inputType = getInputType(this.type, this.conditionValue);
        },
        setValue(event) {
            this.$emit('change-value', event);
        },
        visibleInput() {
            return this.inputType != null;
        },
        getInputType() {
            return getInputType(this.type, this.conditionValue);
        },
        getInputProps() {
            return { index: this.index, value: this.value, field: this.field, type: this.type };
        },
    },
};

const SearchInput = {
    template: "#search-input-template",
    props: ['index', 'value', 'field'],
    data() {
        let value = '';
        if (this.value != null && this.value != undefined) {
            value = this.value.value;
        }
        return {
            currentValue: value,
        };
    },
    methods: {
        setValue(event) {
            this.$emit('change-value', {index: this.index, value: {value: this.currentValue}});
        },
    },
};

const SearchNone = {
    template: "#search-input-none-template",
    props: ['index', 'value', 'field'],
    data() {
        return {
            currentValue: '',
        };
    },
    methods: {
        setValue(event) {
            this.$emit('change-value', {index: this.index, value: {value: this.currentValue}});
        },
    },
};

const SearchInputDate = {
    template: "#search-input-date-template",
    props: ['index', 'value', 'field'],
    data() {
        let value = '';
        if (this.value != null && this.value != undefined) {
            value = this.value.value;
        }
        return {
            currentValue: value,
        };
    },
    methods: {
        setValue(field) {
            this.$emit('change-value', {index: this.index, value: {value: this.currentValue}});
        },
    },
};

const SearchInputDateRange = {
    template: "#search-input-date-range-template",
    props: ['index', 'value', 'field'],
    data() {
        let dateFrom = '';
        let dateTo = '';
        if (this.value != null && this.value != undefined) {
            if (this.value.date_from) {
                dateFrom = this.value.date_from;
            } else if (this.value.value) {
                dateFrom = this.value.value;
            }
            if (this.value.date_to) {
                dateTo = this.value.date_to;
            }
        }
        return {
            currentDateFrom: dateFrom,
            currentDateTo: dateTo,
            dateFormat: 'MM/DD/YYYY hh:mm A',
        };
    },
    methods: {
        setValue(event) {
            this.$emit('change-value', {index: this.index, value: {dateFrom: this.currentDateFrom, dateTo: this.currentDateTo}});
        },
    },
};

const SearchInputDateFixed = {
    template: "#search-input-date-fixed-template",
    props: ['index', 'value', 'field'],
    data() {
        return {
            currentValue: '',
        };
    },
    methods: {
        setValue(event) {
            this.$emit('change-value', {index: this.index, value: {value: this.currentValue}});
        },
    },
};

const SearchInputDateTime = {
    template: "#search-input-date-time-template",
    props: ['index', 'value', 'field'],
    data() {
        let value = '';
        if (this.value != null && this.value != undefined) {
            value = this.value.value;
        }
        return {
            currentValue: value,
        };
    },
    methods: {
        setValue(field) {
            this.$emit('change-value', {index: this.index, value: {value: this.currentValue}});
        },
    },
};

const SearchInputDateTimeRange = {
    template: "#search-input-date-time-range-template",
    props: ['index', 'value', 'field'],
    data() {
        let dateFrom = '';
        let dateTo = '';
        if (this.value != null && this.value != undefined) {
            if (this.value.date_from) {
                dateFrom = this.value.date_from;
            } else if (this.value.value) {
                dateFrom = this.value.value;
            }
            if (this.value.date_to) {
                dateTo = this.value.date_to;
            }
        }
        return {
            currentDateFrom: dateFrom,
            currentDateTo: dateTo,
            dateFormat: 'MM/DD/YYYY hh:mm A',
        };
    },
    methods: {
        setValue(event) {
            this.$emit('change-value', {index: this.index, value: {dateFrom: this.currentDateFrom, dateTo: this.currentDateTo}});
        },
    },
};

const SearchInputDateTimeFixed = {
    template: "#search-input-date-time-fixed-template",
    props: ['index', 'value', 'field'],
    data() {
        return {
            currentValue: '',
        };
    },
    methods: {
        setValue(event) {
            this.$emit('change-value', {index: this.index, value: {value: this.currentValue}});
        },
    },
};

const SearchInputNumericRange = {
    template: "#search-input-numeric-range-template",
    props: ['index', 'value', 'field'],
    data() {
        let from = '';
        let to = '';
        if (this.value != null && this.value != undefined) {
            if (this.value.from) {
                from = this.value.from;
            } else if (this.value.value) {
                from = this.value.value;
            }
            if (this.value.to) {
                to = this.value.to;
            }
        }
        return {
            currentFrom: from,
            currentTo: to,
        };
    },
    methods: {
        setValue(event) {
            this.$emit('change-value', {index: this.index, value: {from: this.from, to: this.to}});
        },
    },
};

const SearchSelect = {
    template: "#search-input-select-template",
    props: ['index', 'value', 'field'],
    data() {
        let value = '';
        if (this.value != null && this.value != undefined) {
            value = this.value.value;
        }
        return {
            currentValue: value,
            options: this.field.options || {},
            empty: this.field.empty || '[Select]',
        };
    },
    methods: {
        setValue(event) {
            this.$emit('change-value', {index: this.index, value: {value: this.currentValue}});
        },
    },
};

const SearchSelectMultiple = {
    template: "#search-input-multiple-template",
    props: ['index', 'value', 'field'],
    data() {
        let value = '';
        if (this.value != null && this.value != undefined) {
            value = this.value.value;
        }
        return {
            currentValue: value,
            options: this.field.options || {},
            empty: this.field.empty || '[Select]',
        };
    },
    methods: {
        setValue(event) {
            this.$emit('change-value', {index: this.index, value: {value: this.currentValue}});
        },
    },
};

const SearchMultiple = {
    template: "#search-multiple-list-template",
    props: ['index', 'value', 'field', 'type'],
    data() {
        return {
            components: [],
            values: this.value || {},
        };
    },
    methods: {
        getProps(index) {
            let idx = this.components.findIndex(el => el.itemIndex == index);
            return {field: this.field, value: this.value, index: this.index, ...this.components[idx]}
        },
        add: function(options) {
            options = options || {
                value: this.value,
            };
            this.components.push({
                field: this.field,
                index: this.index,
                itemType: this.type,
                type: 'SearchMultipleItem',
                itemIndex: this.components.length,
                key: uuid(),
                ...options
            });
        },

        removeItem(index) {
            let idx = this.components.findIndex(el => el.itemIndex == index);
            this.components = this.components.filter(el => el.itemIndex != index)
            this.updateComponentsIndex();
        },
        updateComponentsIndex() {
            const keys = this.components.keys();
            for (let x of keys) {
              this.components[x].itemIndex = x;
            }
        },
        setValue(evt) {
            this.values[evt.itemIndex] = evt.value;
            this.$emit('change-value', {index: this.index, value: this.values});
        },
    },
    mounted() {
            if (this.value != undefined && this.value != null) {
            const keys = Object.keys(this.value);

            for (let v of keys) {
                this.add({value: this.value[v]});
            }
            if (keys.length == 0) {
                this.add();
            }
        } else {
            this.add();
        }
    },
};

const SearchMultipleItem = {
    template: "#search-multiple-item-template",
    props: ['name', 'type', 'index', 'itemIndex', 'value', 'field', 'itemType'],
    data() {
        return {
            conditionValue: this.condition,
        };
    },
    methods: {
        closeItem(index) {
            this.$emit('close-item', this.itemIndex)
        },
        setValue(event) {
            this.$emit('change-value', {index: this.index, itemIndex: this.itemIndex, ...event});
        },
        visibleInput() {
            return true;
        },
        getInputType() {
            return getInputType(this.itemType, '=');
        },
        getInputProps() {
            return { index: this.index, value: this.value, field: this.field };
        },
    },

};

const SearchLookupInput = {
    template: "#search-input-lookup-template",
    props: ['index', 'value', 'field'],
    data() {
        return {
            inputValue: '',
            selectedId: '',
            suggestions: [],
            showSuggestions: false,
            debounceTimeout: null,
            arrowCounter: -1,
            isLoading: false
        };
    },
    computed: {
        autocompleteUrl() {
            return this.field.autocompleteUrl;
        },
        idName() {
            return this.field.idName || 'id';
        },
        valueName() {
            return this.field.valueName || 'name';
        },
        query() {
            return this.field.query || `${this.valueName}=%QUERY`;
        },
        wildcard() {
            return this.field.wildcard || '%QUERY';
        },
    },
    methods: {
        onInput() {
            clearTimeout(this.debounceTimeout);
            this.debounceTimeout = setTimeout(() => {
                this.fetchSuggestions();
            }, 200);
        },
        async fetchSuggestions() {
            if (this.inputValue.length >= 2) {
                try {
                    this.isLoading = true;
                    let query = this.query.replace(this.wildcard, this.inputValue)
                    let autocompleteUrl = this.autocompleteUrl + '?' + query;
                    if (!/^https?:\/\//i.test(autocompleteUrl)) {
                        autocompleteUrl = `${window.location.origin}${autocompleteUrl}`;
                    }
                    const url = new URL(autocompleteUrl);
                    const response = await fetch(url);
                    this.suggestions = await response.json();
                    this.showSuggestions = true;
                    this.arrowCounter = -1;
                } catch (error) {
                    console.error('Error fetching suggestions:', error);
                } finally {
                    this.isLoading = false;
                }
            } else {
                this.isLoading = false;
                this.suggestions = [];
                this.showSuggestions = false;
            }
        },
        selectSuggestion(suggestion) {
            this.inputValue = suggestion[this.valueName];
            this.selectedId = suggestion[this.idName];
            this.showSuggestions = false;
            this.$emit('change-value', { index: this.index, value: { id: this.selectedId, value: this.inputValue } });
        },
        onArrowDown(evt) {
            if (this.showSuggestions) {
                if (this.arrowCounter < this.suggestions.length - 1) {
                    this.arrowCounter++;
                }
                this.scrollToActive();
                evt.preventDefault();
            }
        },
        onArrowUp(evt) {
            if (this.showSuggestions) {
                if (this.arrowCounter > 0) {
                    this.arrowCounter--;
                }
                this.scrollToActive();
                evt.preventDefault();
            }
        },
        onEnter(event) {
            if (this.showSuggestions && this.arrowCounter > -1 && this.arrowCounter < this.suggestions.length) {
                event.preventDefault();
                this.selectSuggestion(this.suggestions[this.arrowCounter]);
            }
        },
        onEscape() {
            if (this.showSuggestions) {
                this.showSuggestions = false;
                this.arrowCounter = -1;
                this.$el.querySelector('input[type="text"]').focus();
            }
        },
        scrollToActive() {
            const activeItem = this.$el.querySelector('.is-active');
            if (activeItem) {
                activeItem.scrollIntoView({ block: 'nearest' });
            }
        },
        onBlur() {
            setTimeout(() => {
                this.showSuggestions = false;
                this.arrowCounter = -1;
            }, 200);
        }
    },
    mounted() {
        if (this.value) {
            this.inputValue = this.value.value || '';
            this.selectedId = this.value.id || '';
        }
    },
    watch: {
        value(newValue) {
            if (newValue) {
                this.inputValue = newValue.value || '';
                this.selectedId = newValue.id || '';
            } else {
                this.inputValue = '';
                this.selectedId = '';
            }
        }
    }
};

const createMyApp = (root, callback) => {
    const app = Vue.createApp(SearchApp);
    app.component('AddNewFilter', AddNewFilter);
    app.component('SearchItem', SearchItem);
    app.component('SearchCondition', SearchCondition);
    app.component('SearchInput', SearchInput);
    app.component('SearchInputDate', SearchInputDate);
    app.component('SearchInputDateFixed', SearchInputDateFixed);
    app.component('SearchInputDateRange', SearchInputDateRange);
    app.component('SearchInputDateTime', SearchInputDateTime);
    app.component('SearchInputDateTimeFixed', SearchInputDateTimeFixed);
    app.component('SearchInputDateTimeRange', SearchInputDateTimeRange);
    app.component('SearchInputNumericRange', SearchInputNumericRange);
    app.component('SearchNone', SearchNone);
    app.component('SearchSelect', SearchSelect);
    app.component('SearchSelectMultiple', SearchSelectMultiple);
    app.component('SearchMultiple', SearchMultiple);
    app.component('SearchMultipleItem', SearchMultipleItem);
    app.component('SearchLookupInput', SearchLookupInput);
    if (callback != undefined) {
        callback(app, registerConditions);
    }
    window._search.rootElemId = root;
    app.mount('#' + root);
    window._search.app = app;
};
window._search.rootElemId = SearchApp.rootElemId;
window._search.createMyApp = createMyApp;

