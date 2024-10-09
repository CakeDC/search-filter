<script type="text/x-template" id="search-list">
    <div class="search-filter-container">
        <Teleport :to="teleportTarget" v-if="shouldTeleport">
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
        </Teleport>

        <div v-else class="search-filter-header">
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

<script type="text/x-template" id="search-select2-template">
  <div>
    <select class="form-control" :id="id" :name="name" :disabled="disabled" :required="required"></select>
  </div>
</script>

<script type="text/x-template" id="search-input-select-template">
    <span>
        <template v-if="mode === 'select2'">
            <Select2
                :id="'select-' + index"
                :name="'v[' + index + '][value][]'"
                v-model="currentValue"
                :options="options"
                :placeholder="empty"
                @update:modelValue="setValue"
            />
        </template>
        <template v-else>
            <select
                class="form-control value"
                :id="'select-' + index"
                :name="'v[' + index + '][value][]'"
                v-model="currentValue"
                @change="setValue($event.target.value)"
            >
                <option value="">{{ empty }}</option>
                <option v-for="(text, id) in options" :key="id" :value="id">
                    {{ text }}
                </option>
            </select>
        </template>
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
            <i class="fa fa-plus"></i>&nbsp;Add&nbsp;Value
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
	<div class="lookup-wrapper">
		<div class="input-wrapper">
			<input
				type="text"
				class="form-control value typeahead"
				:name="'v[' + index + '][value][]'"
				v-model="inputValue"
				autocomplete="off"
				@input="onInput"
				@keydown.down="onArrowDown"
				@keydown.up="onArrowUp"
				@keydown.enter="onEnter"
				@keydown.esc="onEscape"
				@blur="onBlur"
			/>
			<div v-if="isLoading" class="loading-icon">
				<i class="fa fa-spinner fa-spin"></i>
			</div>
		</div>
		<ul v-if="showSuggestions" class="suggestions-list" role="listbox">
			<li
				v-for="(suggestion, index) in suggestions"
				:key="suggestion[idName]"
				@click="selectSuggestion(suggestion)"
				@mouseenter="arrowCounter = index"
				:class="{ 'is-active': index === arrowCounter }"
				role="option"
			>
				{{ suggestion[valueName] }}
			</li>
		</ul>
		<input
			type="hidden"
			:name="'v[' + index + '][id][]'"
			v-model="selectedId"
		/>
	</div>
</script>
