<template>
    <div class="container-fluid">
        <transition name="fade">
            <div class="loader loader-fixed" v-show="loading">Loading...</div>
        </transition>
        <div class="border border-2 mb-3 rounded bg-light">
            <h2>Report Builder</h2>

            <section class="mb-3 p-3">
                <select class="form-select" aria-label="Default select example" v-model="selectedReport"
                    @change="saveReportChanged($event)">
                    <option selected value="">Select saved report</option>
                    <template v-for="(report, index) in reports" :key="field">
                        <option :value="report">{{ report.name }}</option>
                    </template>
                </select>
            </section>

            <section class="p-3">
                <div class="p-3 bg-info bg-opacity-10 border border-info border-3 border-start-3 border-top-0 border-bottom-0"
                    role="alert">
                    Select Fields to generate report
                </div>
                <div class="list-group-item" v-for="(element, field) in fields" :key="element.name">
                    <p class="text-capitalize fw-bold mb-0">{{ field }}</p>
                    <template v-for="(index, field) in element.fields" :key="field">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" v-model="reportFields" :value="index"
                                :id="index.key">
                            <label class="form-check-label" :for="index.key">
                                {{ index.text }}
                            </label>
                        </div>
                    </template>

                </div>
            </section>

            <section v-if="reportFields.length > 0" class="p-3">
                <div class="d-flex align-items-center mb-1">
                    <p class="text-capitalize fw-bold mb-0 me-3">Selected Fields</p>
                    <small class="fst-italic">Drag-Drop fields to arrange in order for a report</small>
                </div>
                <VueDraggableNext class="dragArea w-full d-flex" :list="reportFields" @change="log">
                    <div class="bg-secondary text-white text-center border-1 p-2 me-1" v-for="element in reportFields"
                        :key="element.key">
                        {{ element.text }}
                    </div>
                </VueDraggableNext>
            </section>

            <section v-if="reportFields.length > 0" class="p-3">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="inputPassword6" class="col-form-label">GroupBy</label>
                    </div>
                    <div class="col-auto">
                        <select v-model="groupBy" class="form-select" placeholder="Select Option">
                            <option disabled selected>Select Field</option>
                            <template v-for="(option, i) in reportFields" :key="i">
                                <option :value="option.key">
                                    {{ option.text }}
                                </option>
                            </template>
                        </select>
                    </div>
                    <div class="col-auto">
                        <span id="passwordHelpInline" class="form-text text-info">
                            Select GroupBy Field to group the report by selected field, or leave it blank.
                        </span>
                    </div>
                </div>
            </section>

            <section v-if="reportFields.length > 0" class="p-3 d-flex flex-column">
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-primary" @click="addGroup">Add Group</button>
                </div>
                <template v-for="(group, index) in queryGroups" :key="index">
                    <template v-if="index != 0">
                        <select class="form-select form-select-sm" style="width:75px; background-color: #b2cce5;"
                            v-model="queryGroups[index].condition">
                            <option value="AND">AND</option>
                            <option value="OR">OR</option>
                        </select>
                    </template>
                    <RuleGroup :fields="reportFields" :index="group.id" />
                </template>
            </section>

            <section v-if="reportFields.length > 0" class="p-3">
                <button class="btn btn-success" @click="genrateReport">Generate</button>
            </section>

            <section class="p-3" v-if="reportFields.length > 0">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Report Name" aria-label="Report name"
                            v-model="reportName">
                        <button class="btn btn-primary" type="button" @click="saveReport">Save Report</button>
                    </div>
                </div>
            </section>
        </div>

        <section class="card">
            <div class="card-body">

                <div class="d-flex justify-content-end align-items-center mb-3">
                    <div class="d-flex justify-content-center align-items-center">
                        <label class="form-label m-0 me-2">PerPage:</label>
                        <select v-model="limit" class="form-select" @change="updatePageLimit()">
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

                <div class="mb-3">
                    <PaginationComponent class="card-pagination" :data="data" :loading="loading" @change="changePage" />
                </div>
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
                <div>
                    <PaginationComponent class="card-pagination" :data="data" :loading="loading" @change="changePage" />
                </div>
            </div>
        </section>

    </div>


</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';
import PaginationComponent from '../../components/PaginationComponent.vue';
import RuleGroup from './RuleGroup.vue';
import { useQueryGroup } from '../../composables/useQueryGroup';
import { VueDraggableNext } from 'vue-draggable-next';

const route = useRoute();

const {
    queryGroups,
    addQueryGroup,
} = useQueryGroup();

const loading = ref(false);
const fields = ref([]);
const reportFields = ref([]);
const groupBy = ref('');
const reportUrl = ref('/generate-report');
const limit = ref(200);
const orderBy = ref('');
const orderDirection = ref('');
const selectedReport = ref({});

const data = ref({
    current_page: route.query.p || 1,
    to: 1,
    data: {}
});

const filters = ref([]);
const headers = ref([]);
const defaultGroupId = `group-${Math.random()}`;
const reports = ref([]);
const reportName = ref('');

onMounted(() => {
    getReportColumns();
    getSavedReports();
    addQueryGroup(defaultGroupId);
});

const getReportColumns = () => {
    axios.get('/get-dynamic-reports').then((response) => {
        fields.value = response.data.data;
    });
}

const genrateReport = () => {
    loading.value = true;
    axios.get(reportUrl.value, {
        params: {
            fields: reportFields.value,
            groupBy: groupBy.value,
            limit: limit.value,
            order: order.value,
            filter: JSON.stringify(queryGroups.value)
        }
    }).then((response) => {
        data.value = response.data.data.records;
        filters.value = response.data.data.filters;
        headers.value = reportFields.value;

        loading.value = false;
    }).finally(() => {
        loading.value = false;
    });
}

const getSavedReports = () => {
    axios.get('/get-saved-reports').then((response) => {
        reports.value = response.data.data;
    })
}

const setOrder = (key) => {
    orderDirection.value = orderDirection.value == '' ? 'DESC' : orderDirection.value == 'DESC' ? 'ASC' : '';
    orderBy.value = orderDirection.value == '' ? '' : key;
    genrateReport();
}

const changePage = (page) => {
    if (!loading.value) {
        if (page === data.value.current_page) return;
        data.value.current_page = page;
        genrateReport();
    }
}

const saveReport = () => {
    if (reportName.value != '') {
        axios.post('/save-report', {
            "name": reportName.value,
            fields: {
                fields: reportFields.value,
                groupBy: groupBy.value,
                limit: limit.value,
                order: order.value,
                filter: JSON.stringify(queryGroups.value)
            }
        })
            .then((response) => {
                console.log(response);
            })
    }
}

const order = computed(() => {
    return JSON.stringify({
        orderBy: orderBy.value,
        orderDirection: orderDirection.value
    });
});

const saveReportChanged = (event) => {
    console.log('selected report', selectedReport.value);
    console.log(JSON.parse(selectedReport.value.fields));
}

const addGroup = () => {
    addQueryGroup(`group-${Math.random()}`);
}

const log = (e) => {
    if (data.value.data.length) {
        genrateReport();
    }
}

watch(limit, (value) => {
    genrateReport();
});
</script>