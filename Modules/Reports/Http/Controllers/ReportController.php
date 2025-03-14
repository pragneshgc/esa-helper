<?php

namespace Modules\Reports\Http\Controllers;

use Illuminate\Http\Request;

use Modules\Reports\Models\DynamicReport;
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'fields' => 'nullable'
        ]);

        DynamicReport::create([
            'name' => $validated['name'],
            'fields' => json_encode($validated['fields'])
        ]);

        return sendResponse(true, 'Report saved successfully!');
    }

    public function getSavedReports()
    {
        $reports = DynamicReport::all();
        return sendResponse($reports, 'Success');
    }

    public function update($id, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'fields' => 'nullable'
        ]);
        DynamicReport::where('id', $id)->update([
            'name' => $validated['name'],
            'fields' => json_encode($validated['fields'])
        ]);

        return sendResponse([], 'Report updated successfully!');
    }

    public function delete($id, Request $request)
    {
        DynamicReport::where('id', $id)->delete();
        return sendResponse([], 'Report deleted successfully!');
    }
}
