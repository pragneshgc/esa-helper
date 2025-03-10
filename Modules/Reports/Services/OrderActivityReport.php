<?php

namespace Modules\Reports\Services;

use Modules\Reports\Contracts\ReportContract;
use Modules\Reports\Enums\FilterOperator;
use Modules\Reports\Enums\FilterType;
use Modules\Reports\Enums\SortDirection;

class OrderActivityReport implements ReportContract
{
    public string $table = 'Activity';
    public string $reportName = 'OrderActivity';
    public string $orderBy = 'Activity.ActivityID';
    public string $orderDirection = SortDirection::DESCENDING;

    public function fields(): array
    {
        return [
            [
                'key' => 'Activity.Name',
                'text' => 'User.Name'
            ],
            [
                'key' => 'Activity.OrderID',
                'text' => 'OrderID'
            ],
            [
                'key' => 'Activity.Action',
                'text' => 'Action'
            ],
            [
                'key' => 'Activity.Date',
                'text' => 'Activity.DateTime',
                'dateFormat' => 'd/m/y H:i',
            ],
        ];
    }

    /**
     * Return column list
     * @return <string,string>array
     */
    public function columns(): array
    {
        return [
            'fields' => $this->fields(),
        ];
    }

    public function includedJoins(): array
    {
        return [
            'Prescription' => [
                'Prescription.PrescriptionID',
                '=',
                'Activity.OrderID'
            ]
        ];
    }
}
