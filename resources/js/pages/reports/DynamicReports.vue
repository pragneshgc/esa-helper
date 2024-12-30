<template>
    <div class="container-fluid">

        <transition name="fade">
            <div class="loader loader-fixed" v-show="loading">Loading...</div>
        </transition>

        <h2>Report Dashboard</h2>

        <div class="row">
            <div class="col">
                <h3>Table Fields</h3>

                <div class="list-group-item" v-for="(element, field) in fields" :key="element.name">
                    <ul class="list-group-ul">
                        <span>{{ field }}</span>
                        <draggable class="list-group" :list="element.fields" group="people">
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
                    <draggable class="list-group" v-model="reportFields" :list="reportFields" group="people">
                        <li class="list-group-li" v-for="(index, field) in reportFields">
                            <span class="list-item">{{ index.text }}</span>
                        </li>
                    </draggable>
                </ul>
                <button type="button" class="btn btn-primary" @click="getData()"
                    :disabled="reportFields.length < 3">Generate Report</button>
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
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </span>
                                </div>

                            </th>
                        </tr>
                        <tr>
                            <th v-for="(header, index) in headers" :key="index" scope="col">
                                <div class="input-group" v-if="showFilters.includes(header.key)">
                                    <input type="text" class="form-control" v-model="filters[header.key]"
                                        :placeholder="header.text" />
                                    <span class="input-group-text" id="basic-addon1" @click="search()">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </span>
                                    <span class="input-group-text bg-danger" id="basic-addon2"
                                        @click="hideFilter(header.key)">
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
            filters: [],
            limit: '200',
            loading: false,
            showFilters: [],
        }
    },
    components: {
        draggable: VueDraggableNext,
        'PaginationComponent': defineAsyncComponent(() => import('../../components/PaginationComponent.vue'))
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
        },
        search() {
            console.log(this.filters);
            this.getData();
        },
        getReportColumns() {
            axios.get('/get-dynamic-reports').then((response) => {
                this.fields = response.data.data;
            });
        },
        changePage: function (page) {
            console.log('report changepage');
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
                }
            })
                .then((response) => {
                    this.data = response.data.data;
                    this.headers = this.reportFields;
                    Object.values(this.headers).forEach((item) => {
                        console.log(item, item.key);
                        this.filters[item.key] = '';
                    })
                    this.loading = false;
                })
                .catch((error) => {
                    console.log(error);
                })
        },
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
            console.log('filter param', this.filters);
            //return this.filters != '' ? JSON.stringify(this.filters) : '';
            return JSON.stringify(this.filters);
        },
    },
    watch: {
        limit: 'getData'
    }
}
</script>
