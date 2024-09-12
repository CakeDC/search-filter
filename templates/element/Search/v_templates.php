<?php
/**
 * @var \App\View\AppView $this
 */
?>
<style>
.search-filter-container {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}

.search-filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.search-filter-title {
    font-size: 1.2em;
    font-weight: bold;
    color: #495057;
}

.search-filter-actions {
    display: flex;
    gap: 10px;
}

.btn-clear-filters,
.btn-add-filter {
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 0.9em;
    transition: all 0.3s ease;
}

.btn-clear-filters {
    background-color: #e9ecef;
    color: #495057;
    border: none;
}

.btn-clear-filters:hover {
    background-color: #dee2e6;
}

.btn-add-filter {
    background-color: #007bff;
    color: white;
    border: none;
}

.btn-add-filter:hover {
    background-color: #0056b3;
}

.search-filter-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.search-filter-item.item-alert {
    background-color: #d1e1f0;
}

.search-filter-item {
    background-color: white;
    border: 1px solid #ced4da;
    border-radius: 6px;
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.3s ease;
}

.search-filter-item:hover {
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.search-filter-item-content {
    flex-grow: 1;
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.search-filter-item label {
    font-weight: bold;
    color: #495057;
    margin-bottom: 0;
    min-width: 100px;
}

.search-filter-item select,
.search-filter-item input {
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 5px 10px;
    flex-grow: 1;
}

.search-filter-item .btn-remove-filter {
    background: none;
    border: none;
    color: #dc3545;
    cursor: pointer;
    font-size: 1.2em;
    padding: 0;
}

.search-filter-item .btn-remove-filter:hover {
    color: #a71d2a;
}

.search-empty-state {
    text-align: center;
    padding: 20px;
    background-color: #e9ecef;
    border-radius: 6px;
    color: #6c757d;
}

@media (max-width: 768px) {
    .search-filter-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .search-filter-item-content {
        flex-direction: column;
        align-items: stretch;
    }

    .search-filter-item label {
        min-width: auto;
    }
}

.search-multiple-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 10px;
}

.search-multiple-add {
    align-self: flex-start;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 5px 10px;
    font-size: 0.9em;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.search-multiple-add:hover {
}

.search-multiple-item {
    display: flex;
    align-items: center;
    gap: 10px;
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 10px;
}

.search-multiple-item .input-area {
    flex-grow: 1;
    display: flex;
    gap: 10px;
}

.search-multiple-item input,
.search-multiple-item select {
    flex-grow: 1;
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 5px 10px;
}

.search-multiple-item .btn-remove-item {
    background: none;
    border: none;
    color: #dc3545;
    cursor: pointer;
    font-size: 1.2em;
    padding: 0;
}

.search-multiple-item .btn-remove-item:hover {
    color: #a71d2a;
}

@media (max-width: 768px) {
    .search-multiple-item {
        flex-direction: column;
        align-items: stretch;
    }

    .search-multiple-item .input-area {
        flex-direction: column;
    }
}

.add-new-filter {
    display: inline-block;
}

.add-new-filter-content {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 10px;
}

.add-new-filter label {
    font-weight: bold;
    color: #495057;
    margin-bottom: 0;
}

.add-new-filter select {
    min-width: 200px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 6px 12px;
    background-color: white;
    font-size: 0.9em;
}

.add-new-filter select:focus {
    outline: none;
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

@media (max-width: 768px) {
    .add-new-filter-content {
        flex-direction: column;
        align-items: stretch;
    }

    .add-new-filter select {
        width: 100%;
    }
}

.search-filter-btn {
    padding: 6px 12px;
    border-radius: 4px;
    border: 1px solid #ced4da;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
    background-color: #f8f9fa;
    color: #495057;
}

.search-filter-btn:hover {
    background-color: #e2e6ea;
    border-color: #dae0e5;
    color: #495057;
}

.search-filter-actions {
    display: flex;
    gap: 10px;
}

.search-multiple-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 10px;
}

.search-multiple-add {
    align-self: flex-start;
}

.multiple-item {
    margin-top: 10px;
    margin-left: 15px;
}
.fa.fa-times {
    margin-right: 5px;
    margin-top: 5px;
}
.conditions {
    align-items: center;
}
.hidden {
    display: none !important;
}

/* lookup widget */
.lookup-wrapper {
    position: relative;
    display: inline-block;
}

.lookup-wrapper input {
    width: 100%;
}

.suggestions-list {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    z-index: 1000;
    display: block;
    padding: 5px 0;
    margin: 2px 0 0;
    font-size: 1rem;
    text-align: left;
    list-style: none;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,.15);
    border-radius: 4px;
    box-shadow: 0 6px 12px rgba(0,0,0,.175);
    max-height: 200px;
    overflow-y: auto;
}

.suggestions-list li {
    padding: 3px 20px;
    clear: both;
    font-weight: 400;
    line-height: 1.42857143;
    color: #333;
    white-space: nowrap;
    cursor: pointer;
}

.suggestions-list li:hover {
    color: #262626;
    text-decoration: none;
    background-color: #f5f5f5;
}

</style>

<script type="text/x-template" id="search-list">
    <div class="search-filter-container">
        <div class="search-filter-header">
            <h3 class="search-filter-title"></h3>
            <div class="search-filter-actions">
                <a class="search-filter-btn" @click="removeAll()">Clear Filters</a>
                <AddNewFilter
                    :fields="fields"
                    :components="components"
                    @add-filter="addFilter($event)"
                />
            </div>
        </div>

        <div v-if="emptyFilters()" class="search-empty-state">
            <p>There are currently no filters. Please add one to start search.</p>
        </div>

        <div v-else class="search-filter-list">
            <component v-for="component in components"
                :id="'filter-' + component.filter"
                :is="component.type"
                :key="component.key"
                v-bind="getProps(component.type, component.index)"
                @close-item="removeItem($event)"
                @select-filter="selectFilter($event, index)"
                @select-condition="selectCondition($event)"
                @change-value="setValue($event)"
                class="search-filter-item"
            ></component>
        </div>
    </div>
</script>

<script type="text/x-template" id="search-item-template">
    <div :class="itemClasses" class="search-filter-item">
        <div class="search-filter-item-content">
            <label>{{ fields[search_filter].name }}</label>
            <input
                type="hidden"
                :name="'f[' + index + ']'"
                v-model="search_filter"
            />
            <SearchCondition
                v-if="displayCondition()"
                v-bind="currentField()"
                @change-value="setValue($event)"
                @select-condition="selectCondition($event)"
            />
        </div>
        <button
          type="button"
          class="btn-remove-filter"
          @click="closeItem(index)"
          aria-label="Remove filter"
        >
          <span class="fa fa-times" aria-hidden="true"></span>
        </button>
    </div>
</script>

<script type="text/x-template" id="search-list1">
    <div>
        <div class="mb-10 buttons form-group" :key="appKey">
            <a class="btn btn-xs btn-info btn-remove-items" @click="removeAll()"><?= __('Clear Filters') ?></a>
            <AddNewFilter
                :fields="fields"
                :components="components"
                style="display: inline-block; float:right;"
                @add-filter="addFilter($event)"
            />
        </div>

        <div v-if="emptyFilters()" class="alert alert-warning">
            <p>There are currently no filters. Please add one to start search.</p>
        </div>

        <div class="filter-items">
            <component v-for="component in components"
                :id="'filter-' + component.filter"
                :is="component.type"
                :key="component.key"
                v-bind="getProps(component.type, component.index)"
                @close-item="removeItem($event)"
                @select-filter="selectFilter($event, index)"
                @select-condition="selectCondition($event)"
                @change-value="setValue($event)"
                class="form-inline search-filter-item"
            ></component>
        </div>
    </div>
</script>

<script type="text/x-template" id="search-add-filter-template">
    <div class="add-new-filter">
        <div class="add-new-filter-content">
            <label for="add_filter_select">Add&nbsp;filter</label>
            <select
                id="add_filter_select"
                class="form-control field"
                v-on:change="selectFilter($event)"
                v-model="search_filter"
            >
                <option value="">[Select Field]</option>
                <option
                    v-for="(field, key) in fields"
                    :value="key"
                    :disabled="disabled(key)"
                >
                    {{ field.name }}
                </option>
            </select>
        </div>
    </div>
</script>

<script type="text/x-template" id="search-conditions-template">
    <span class="conditions form-inline">
        <select
            :class="showConditionClasses()"
            class="form-control condition mr-2"
            :name="'c[' + index + ']'"
            @change="changeCondition()"
            v-model="conditionValue"
        >
            <option
                v-for="(field, key) in conditions"
                :value="key"
            >{{ field }}</option>
        </select>
        <component
            class="input-area"
            v-if="visibleInput()"
            :is="inputType"
            :key="inputType"
            @change-value="setValue($event)"
            v-bind="getInputProps()"
        ></component>
    </span>
</script>

<script type="text/x-template" id="search-input-template">
    <span class="input-wrapper">
        <input
            type="text"
            class="form-control value"
            :name="'v[' + index + '][value][]'"
            v-model="currentValue"
            @change="setValue()"
        />
    </span>
</script>

<script type="text/x-template" id="search-input-none-template">
    <span>
        <input
            type="hidden"
            class="form-control value"
            :name="'v[' + index + '][value][]'"
            v-model="currentValue"
        />
    </span>
</script>
<script type="text/x-template" id="search-input-select-template">
    <span>
        <select
            class="form-control value"
            :name="'v[' + index + '][value][]'"
            v-model="currentValue"
            @change="setValue()"
        >
            <option value="">{{ empty || '[Select]' }}</option>
            <option v-for="(field, key) in options" :value="key">{{ field }}</option>
        </select>
    </span>
</script>

<script type="text/template" id="search-input-multiple-template">
    <span>
        <select
            class="form-control value"
            :name="'v[' + index + '][value][]'"
            v-model="currentValue"
            @change="setValue()"
            multiple="multiple"
        >
            <option value="">{{ empty || '[Select]' }}</option>
            <option v-for="(field, key) in options" :value="key">{{ field }}</option>
        </select>
    </span>
</script>


<script type="text/x-template" id="search-input-date-template">
    <span class="date-wrapper">
        <input
            type="date"
            class="form-control value date"
            :name="'v[' + index + '][value][]'"
            v-model="currentValue"
            data-date-format="dateFormat"
            @change="setValue()"
        />
    </span>
</script>

<script type="text/x-template" id="search-input-date-fixed-template">
    <span class="date-fixed-wrapper">
        <input
            type="hidden"
            class="form-control value"
            :name="'v[' + index + '][value][]'"
            v-model="currentValue"
        />
    </span>
</script>

<script type="text/x-template" id="search-input-date-range-template">
    <div class="input-daterange input-group form-inline conditions" id="datepicker">
        <input
            type="date"
            class="form-control value date date-from"
            data-date-orientation="bottom right"
            :name="'v[' + index + '][date_from][]'"
            v-model="currentDateFrom"
            data-date-format="dateFormat"
            @change="setValue('date_from')"
        />
        <span class="input-group-addon centered-element ml-2 mr-2">to</span>
        <input
            type="date"
            class="form-control value date date-to"
            data-date-orientation="bottom right"
            :name="'v[' + index + '][date_to][]'"
            v-model="currentDateTo"
            data-date-format="dateFormat"
            @change="setValue('date_to')"
        />
    </div>
</script>

<script type="text/x-template" id="search-input-date-time-template">
    <span class="date-time-wrapper">
        <input
            type="datetime-local"
            class="form-control value date"
            :name="'v[' + index + '][value][]'"
            v-model="currentValue"
            data-date-format="dateFormat"
            @change="setValue()"
        />
    </span>
</script>

<script type="text/x-template" id="search-input-date-time-fixed-template">
    <span class="date-time-fixed-wrapper">
        <input
            type="hidden"
            class="form-control value"
            :name="'v[' + index + '][value][]'"
            v-model="currentValue"
        />
    </span>
</script>

<script type="text/x-template" id="search-input-date-time-range-template">
    <div class="input-daterange input-group" id="datepicker">
        <input
            type="datetime-local"
            class="form-control value date date-from"
            data-date-orientation="bottom right"
            :name="'v[' + index + '][date_from][]'"
            v-model="currentDateFrom"
            data-date-format="dateFormat"
            @change="setValue('date_from')"
        />
        <span class="input-group-addon">to</span>
        <input
            type="datetime-local"
            class="form-control value date date-to"
            data-date-orientation="bottom right"
            :name="'v[' + index + '][date_to][]'"
            v-model="currentDateTo"
            data-date-format="dateFormat"
            @change="setValue('date_to')"
        />
    </div>
</script>

<script type="text/x-template" id="search-input-numeric-range-template">
    <span>
        <input
            type="number"
            class="form-control value value-from"
            :name="'v[' + index + '][from][]'"
            v-model="currentFrom"
            @change="setValue('from')"
        />
        <input
            type="number"
            class="form-control value value-to"
            :name="'v[' + index + '][to][]'"
            v-model="currentTo"
            @change="setValue('to')"
        />
</script>

<script type="text/x-template" id="search-multiple-list-template">
    <div class="search-multiple-list">
        <a class="search-filter-btn search-multiple-add" @click="add()">
            <i class="fa fa-plus"></i> Add Value
        </a>
        <div class="search-multiple-items">
            <SearchMultipleItem
                v-for="component in components"
                :key="component.key"
                v-bind="getProps(component.itemIndex)"
                @close-item="removeItem($event)"
                @change-value="setValue($event)"
                class="search-multiple-item"
            />
        </div>
    </div>
</script>

<script type="text/x-template" id="search-multiple-item-template">
    <div class="search-multiple-item">
        <div class="input-area">
            <component
                :is="getInputType()"
                @change-value="setValue($event)"
                v-bind="getInputProps()"
            ></component>
        </div>
        <button
            type="button"
            class="btn-remove-item"
            @click="closeItem(itemIndex)"
            aria-label="Remove item"
        >
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
    </div>
</script>

<script type="text/x-template" id="search-input-lookup-template">
  <span class="lookup-wrapper">
    <input
      type="hidden"
      :name="'v[' + index + '][id][]'"
      v-model="selectedId"
    />
    <input
      type="text"
      class="form-control value typeahead"
      :name="'v[' + index + '][value][]'"
      v-model="inputValue"
      autocomplete="off"
      @input="onInput"
      @blur="onBlur"
    />
    <ul v-if="showSuggestions" class="suggestions-list">
      <li
        v-for="suggestion in suggestions"
        :key="suggestion.id"
        @click="selectSuggestion(suggestion)"
      >
        {{ suggestion[valueName] }}
      </li>
    </ul>
  </span>
</script>
