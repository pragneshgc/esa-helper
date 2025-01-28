<?php

namespace Modules\Reports\Http\Controllers;

use Illuminate\Http\Request;

use Modules\Reports\Services\DynamicReports;

class ReportController
{
    private DynamicReports $reports;
    public function __construct(Request $request)
    {
        $this->reports = new DynamicReports();
    }
    public function index(Request $request)
    {
        $columns = $this->reports->getColumns();
        return sendResponse($columns, 'Success');
    }

    public function generate(Request $request)
    {
        $response = $this->reports->newReport($request);
        return sendResponse($response, 'Success');
    }
}
