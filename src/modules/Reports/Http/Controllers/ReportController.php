<?php

namespace Esa\Helper\Modules\Reports\Http\Controllers;

use Illuminate\Http\Request;

use Esa\Helper\Modules\Reports\Services\DynamicReports;

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
        /* $records = Activity::query()->with(['order'])->limit(10)->get();
        $orders = Prescription::query()->with(['activity'])->first();
        dd($records, $orders); */

        $response = $this->reports->newReport($request);
        return sendResponse($response);
    }
}
