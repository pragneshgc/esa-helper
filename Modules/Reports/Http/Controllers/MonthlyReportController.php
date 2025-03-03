<?php

namespace Modules\Reports\Http\Controllers;

use Carbon\Carbon;
use Esa\Helper\Enums\OrderStatus;
use Modules\Reports\Repositories\ReportRepository;

class MonthlyReportController
{
    public function __construct(private ReportRepository $reportRepo) {}
    public function getCurrentMonthReport()
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $start_timestamp = $start->timestamp;
        $end_timestamp = $end->timestamp;
        $start_datetime = $start->format('Y-m-d H:i:s');
        $end_datetime = $end->format('Y-m-d H:i:s');

        $current_batch = $this->reportRepo->getBatchData($start_datetime, $end_datetime);

        $current_expired = $this->reportRepo->getDoopData($start_datetime, $end_datetime, 7);
        $current_damaged = $this->reportRepo->getDoopData($start_datetime, $end_datetime, 8);
        $current_other = $this->reportRepo->getDoopData($start_datetime, $end_datetime, 9);

        return [
            'month' => $start->format('F'),
            'shipped' => $this->reportRepo->getOrderCount($start_timestamp, $end_timestamp, OrderStatus::SHIPPED->value),
            'fridge_shipped' => $this->reportRepo->getShippedFridgeOrder($start_datetime, $end_datetime),
            'return' => $this->reportRepo->getOrderCount($start_timestamp, $end_timestamp, OrderStatus::RETURNED->value),
            'resend' => $this->reportRepo->getOrderCount($start_timestamp, $end_timestamp, OrderStatus::RESEND->value),
            'inventory_unit' => $this->reportRepo->getInventoryUnit($start_datetime, $end_datetime),
            'inventory_value' => number_format($this->reportRepo->getInventoryValue($start_datetime, $end_datetime), 2),
            'batches' => $current_batch->batch_count,
            'batch_items' => number_format($current_batch->batch_items, 2) ?? 0,
            'expired' => $current_expired->doop_count,
            'expired_value' => number_format($current_expired->doop_value, 2),
            'damaged' => $current_damaged->doop_count,
            'damaged_value' => number_format($current_damaged->doop_value, 2),
            'doop_other' => $current_other->doop_count,
            'doop_other_value' => number_format($current_other->doop_value, 2),
        ];
    }

    public function getLastMonthReport()
    {
        $last_start = Carbon::now()->subMonth()->startOfMonth();
        $last_end = Carbon::now()->subMonth()->endOfMonth();

        $lastmonth_start_timestamp = $last_start->timestamp;
        $lastmonth_end_timestamp = $last_end->timestamp;
        $lastmonth_start_datetime = $last_start->format('Y-m-d H:i:s');
        $lastmonth_end_datetime = $last_end->format('Y-m-d H:i:s');


        $last_batch = $this->reportRepo->getBatchData($lastmonth_start_datetime, $lastmonth_end_datetime);
        $last_expired = $this->reportRepo->getDoopData($lastmonth_start_datetime, $lastmonth_end_datetime, 7);
        $last_damaged = $this->reportRepo->getDoopData($lastmonth_start_datetime, $lastmonth_end_datetime, 8);
        $last_other = $this->reportRepo->getDoopData($lastmonth_start_datetime, $lastmonth_end_datetime, 9);
        return [
            'month' => $last_start->format('F'),
            'shipped' => $this->reportRepo->getOrderCount($lastmonth_start_timestamp, $lastmonth_end_timestamp, OrderStatus::SHIPPED->value),
            'fridge_shipped' => $this->reportRepo->getShippedFridgeOrder($lastmonth_start_timestamp, $lastmonth_end_timestamp),
            'return' => $this->reportRepo->getOrderCount($lastmonth_start_timestamp, $lastmonth_end_timestamp, OrderStatus::RETURNED->value),
            'resend' => $this->reportRepo->getOrderCount($lastmonth_start_timestamp, $lastmonth_end_timestamp, OrderStatus::RESEND->value),
            'inventory_unit' => $this->reportRepo->getInventoryUnit($lastmonth_start_datetime, $lastmonth_end_datetime),
            'inventory_value' => number_format($this->reportRepo->getInventoryValue($lastmonth_start_datetime, $lastmonth_end_datetime), 2),
            'batches' => $last_batch->batch_count,
            'batch_items' => number_format($last_batch->batch_items) ?? 0,
            'expired' => $last_expired->doop_count,
            'expired_value' => number_format($last_expired->doop_value, 2),
            'damaged' => $last_damaged->doop_count,
            'damaged_value' => number_format($last_damaged->doop_value, 2),
            'doop_other' => $last_other->doop_count,
            'doop_other_value' => number_format($last_other->doop_value, 2)
        ];
    }
}
