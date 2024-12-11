<?php

namespace Esa\Helper\Modules\Reports\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DynamicReports
{
    private array $tableClass = [];
    private array $registerReports = [
        UserActivityReport::class,
        OrderReport::class,
        ProductReport::class
    ];

    public function __construct()
    {
        foreach ($this->registerReports as $key => $report) {
            $class = new $report;
            $this->tableClass[$class->table] = $report;
        }
    }

    public function getColumns(): array
    {
        $columns = [];
        foreach ($this->registerReports as $key => $report) {
            $class = new $report;
            $columns[$class->reportName] = $class->columns();
        }
        return $columns;
    }

    public function newReport(Request $request)
    {
        $queryBuilder = new QueryBuilder($this->registerReports, $request);
        return $queryBuilder->get();
    }
}
