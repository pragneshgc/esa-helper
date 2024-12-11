<?php

namespace Esa\Helper\Modules\Reports\Services;

use Esa\Helper\Modules\Reports\Enums\SortDirection;
use Esa\Helper\Modules\Reports\Contracts\ReportContract;


class UserActivityReport implements ReportContract
{
    public string $table = 'Activity';
    public string $reportName = 'UserActivity';
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
                'text' => 'User.Activity'
            ],
            [
                'key' => 'Activity.Date',
                'text' => 'Activity.Date'
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
