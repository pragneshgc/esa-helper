<template>
    <div class="container-fluid">

        <transition name="fade">
            <div class="loader loader-fixed" v-show="loading">Loading...</div>
        </transition>

        <h2>Report Dashboard</h2>
        <hr>
        <div class="row">
            <div class="col">

                <div class="list-group-item" v-for="(element, field) in fields" :key="element.name">
                    <ul class="list-group-ul">
                        <span>{{ field }}</span>
                        <draggable class="list-group" :list="element.fields" group="fields">
                            <li class="list-group-li text-center" v-for="(index, field) in element.fields">
                                <span class="list-item">{{ index.text }}</span>
                            </li>
                        </draggable>
                    </ul>
                </div>

            </div>
            <hr>

            <div class="col">
                <h3>Reports Fields</h3>
                <ul class="list-group-ul report-dropbox">
                    <draggable class="list-group" v-model="reportFields" :list="reportFields" group="fields">
                        <li class="list-group-li" v-for="(index, field) in reportFields">
                            <span class="list-item">{{ index.text }}</span>
                        </li>
                    </draggable>
                </ul>

                <div class="row align-items-center mb-3">
                    <div class="col-3">

                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">GroupBy</span>

                            <select v-model="groupBy" class="form-control" placeholder="Select Option"
                                aria-describedby="basic-addon1">
                                <template v-for="(option, i) in reportFields">
                                    <option :value="option.key">
                                        {{ option.text }}
                                    </option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row align-items-center">
                    <div class="col-3">

                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">FilterBy</span>

                            <select v-model="dateFilter" class="form-control" placeholder="Select Option"
                                aria-describedby="basic-addon1">
                                <template v-for="(option, i) in reportFields">
                                    <option :value="option.key">
                                        {{ option.text }}
                                    </option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="col-9">
                        <VueDatePicker v-model="date" range :enable-time-picker="false" format="dd/MM/yyyy">
                        </VueDatePicker>
                    </div>
                </div>

                <div v-if="dateFilter != ''">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" v-model="dateGroupBy" value="" name="groupBy"
                            id="group-none" checked>
                        <label class="form-check-label" for="group-none">
                            None
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" v-model="dateGroupBy" value="hours" name="groupBy"
                            id="group-hours">
                        <label class="form-check-label" for="group-hours">
                            Group By Hours
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" v-model="dateGroupBy" value="days" name="groupBy"
                            id="group-days">
                        <label class="form-check-label" for="group-days">
                            Group By Days
                        </label>
                    </div>
                </div>


                <hr style="margin: 20px 0px">

                <button type="button" class="btn btn-primary" @click="getData()"
                    :disabled="reportFields.length == 0">Generate
                    Report</button>
            </div>
        </div>
        <hr style="margin: 20px 0px">

        <section class="card">
            <div class="card-header">
                <h3>Dynamic Reports</h3>
            </div>
            <div class="card-body">
                <PaginationComponent class="card-pagination" :data="data" :loading="loading" @change="changePage" />

                <div class="filters-row">
                    <div class="filter-inputs">
                        <input v-model="queryString" type="text" class="form-control filter-search" id="queryString"
                            placeholder="Search...">
                        <select v-model="limit" class="table-dropdown" @change="updatePageLimit()">
                            <option value="10">Show 10</option>
                            <option value="20">Show 20</option>
                            <option value="50">Show 50</option>
                            <option value="100">Show 100</option>
                            <option value="200">Show 200</option>
                            <option value="9999999999">Show All</option>
                        </select>
                    </div>
                    <div class="filter-button-group">
                        <a title="Print the results" class="btn btn-primary waves-effect">
                            <i class="fa-solid fa-print" aria-hidden="true"></i>
                        </a>
                        <a title="Download results as PDF" class="btn btn-primary waves-effect">
                            <i class="fa-solid fa-file-pdf" aria-hidden="true"></i>
                        </a>
                        <a title="Download results as CSV" class="btn btn-primary waves-effect">
                            <i class="fa-solid fa-file-csv" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <div class="table-responsive small"></div>
                <table class="table table-striped table-sm">
                    <thead class="thead-dark">
                        <tr class="report-header">
                            <th v-for="(header, index) in headers" :key="index" scope="col">
                                <div class="input-group">
                                    <span class="me-3">{{ header.text }}</span>

                                    <span class="input-group-text me-2" id="basic-addon01"
                                        @click="setOrder(header.key)">
                                        <i v-if="header.key == orderBy && orderDirection == 'DESC'"
                                            class="fa fa-caret-down"></i>
                                        <i v-if="header.key == orderBy && orderDirection == 'ASC'"
                                            class="fa fa-caret-up"></i>
                                        <i v-if="header.key != orderBy" class="fa fa-sort"></i>
                                    </span>
                                    <span class="input-group-text" id="basic-addon02" @click="showFilter(header.key)">
                                        <i class="fa-solid fa-filter"></i>
                                    </span>
                                </div>

                            </th>
                        </tr>
                        <tr>
                            <th v-for="(filter, index) in filters" :key="index" scope="col">
                                <div class="input-group" v-if="showFilters.includes(filter.key)">
                                    <template v-if="filter.type == 'dropdown'">
                                        <select class="form-select" v-model="filter.value">
                                            <option value="">Select {{ filter.text }}</option>
                                            <option v-for="(option, i) in filter.values" :key="i"
                                                :value="option[filter.column]">
                                                {{ option[filter.column] }}
                                            </option>
                                        </select>
                                    </template>
                                    <template v-else-if="filter.type == 'date'">
                                        <input type="date" class="form-control" :placeholder="filter.column"
                                            v-model="filter.value" />
                                    </template>
                                    <template v-else>
                                        <input type="text" class="form-control" :placeholder="filter.column"
                                            v-model="filter.value" />
                                    </template>

                                    <span class="input-group-text" id="basic-addon1" @click="search()">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </span>
                                    <span class="input-group-text bg-danger" id="basic-addon2"
                                        @click="hideFilter(filter.key)">
                                        <i class="fa-solid fa-xmark"></i>
                                    </span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(items, index) in data.data" :key="index">
                            <td v-for="(item, i) in items" :key="i">
                                {{ item }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <PaginationComponent class="card-pagination" :data="data" :loading="loading" @change="changePage" />
            </div>

        </section>
    </div>
</template>
<script>
import axios from 'axios';
import { defineAsyncComponent } from 'vue';
import { VueDraggableNext } from 'vue-draggable-next'
import _ from 'lodash';
import VueDatePicker from '@vuepic/vue-datepicker';
import { groupBy } from 'lodash';

export default {
    mixins: [Error],
    data() {
        return {
            fields: [],
            reportFields: [],
            headers: [],

            dataUrl: '/generate-report',
            data: {
                current_page: this.$route.query.p || 1,
                to: 1,
                data: {}
            },
            queryString: this.$route.query.q || '',
            orderBy: '',
            orderDirection: '',
            filters: {},
            limit: '200',
            loading: false,
            showFilters: [],
            date: null,
            dateFilter: '',
            dateGroupBy: '',
            groupBy: '',
        }
    },
    components: {
        draggable: VueDraggableNext,
        'PaginationComponent': defineAsyncComponent(() => import('../../components/PaginationComponent.vue')),
        VueDatePicker
    },
    mounted() {
        this.getReportColumns();
    },
    methods: {
        showFilter(column) {
            this.showFilters.push(column);
        },
        hideFilter(column) {
            var index = this.showFilters.indexOf(column);
            if (index !== -1) {
                this.showFilters.splice(index, 1);
            }
            this.filters[column].value = '';
            this.getData();
        },
        search() {
            this.getData();
        },
        getReportColumns() {
            axios.get('/get-dynamic-reports').then((response) => {
                this.fields = response.data.data;
            });
        },
        changePage: function (page) {
            if (!this.loading) {
                if (page === this.data.current_page) return;
                this.data.current_page = page;
                this.getData();
            }
        },
        setOrder: function (key) {
            this.orderDirection = this.orderDirection == '' ? 'DESC' : this.orderDirection == 'DESC' ? 'ASC' : '';
            this.orderBy = this.orderDirection == '' ? '' : key;
            this.getData();
        },
        getData() {
            this.loading = true;
            axios.get(this.dataUrl, {
                params: {
                    fields: this.reportFields,
                    page: this.currentPageParam,
                    q: this.currentQueryString,
                    limit: this.currentLimitParam,
                    f: this.currentFilterParam,
                    orderBy: this.currentOrderByParam,
                    orderDirection: this.currentOrderDirectionParam,
                    groupBy: this.groupBy,
                    dateOptions: this.dateOptions,
                }
            })
                .then((response) => {
                    this.data = response.data.data.records;
                    this.filters = response.data.data.filters;
                    this.headers = this.reportFields;

                    this.loading = false;
                })
                .catch((error) => {
                    console.log(error);
                })
        }
    },
    computed: {
        currentPageParam: function () {
            if (this.data.current_page) {
                return this.data.current_page
            } else {
                return '1'
            }
        },
        currentQueryString: function () {
            return this.queryString != '' ? this.queryString : '';
        },
        currentOrderByParam: function () {
            return this.orderBy != '' ? this.orderBy : '';
        },
        currentOrderDirectionParam: function () {
            return this.orderDirection != '' ? this.orderDirection : '';
        },
        currentLimitParam: function () {
            return this.limit != '' ? this.limit : '';
        },
        currentFilterParam: function () {
            let searchFilter = {};
            Object.values(this.filters).forEach((filter) => {
                if (filter.value != '') {
                    searchFilter[filter.key] = filter.value;
                }
            });
            console.log('filter', _.isEmpty(searchFilter), JSON.stringify(searchFilter));
            return _.isEmpty(searchFilter) ? {} : JSON.stringify(searchFilter);
        },
        dateOptions() {
            return {
                dateRange: this.date,
                dateFitler: this.dateFilter,
                dateGroupBy: this.dateGroupBy,
            }
        }
    },
    watch: {
        limit: 'getData',
    }
}
</script>
