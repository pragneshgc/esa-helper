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
                <PaginationComponent class="card-pagination" :data="data" :loading="loading" @click="changePage" />

                <div class="filters-row">
                    <div class="filter-inputs">
                        <input v-model="queryString" type="text" class="form-control filter-search" id="queryString"
                            placeholder="Search...">
                        <select v-model="limit" class="table-dropdown">
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

                <table class="table table-hover">
                    <thead class="thead-dark primary-color">
                        <th v-for="(header, index) in headers" :key="index" scope="col"
                            v-on:click="setOrder(header.key)">
                            {{ header.text }}

                            <span>
                                <i v-if="header.key == orderBy && orderDirection == 'DESC'"
                                    class="fa fa-caret-down"></i>
                                <i v-if="header.key == orderBy && orderDirection == 'ASC'" class="fa fa-caret-up"></i>
                                <i v-if="header.key != orderBy" class="fa fa-sort"></i>
                            </span>

                            <span style="margin-left: 10px; cursor: pointer;">
                                <i class="fa fa-filter" aria-hidden="true"></i>
                            </span>
                        </th>
                    </thead>
                    <tbody>
                        <tr v-for="(items, index) in data.data" :key="index">
                            <td v-for="(item, i) in items" :key="i">
                                {{ item }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <PaginationComponent class="card-pagination" :data="data" :loading="loading" @click="changePage" />
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
            filters: {},
            limit: '200',
            loading: false,
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
                }
            })
                .then((response) => {
                    this.data = response.data.data;
                    this.headers = this.reportFields;
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
            return this.filters != '' ? this.filters : '';
        },
    }
}
</script>
