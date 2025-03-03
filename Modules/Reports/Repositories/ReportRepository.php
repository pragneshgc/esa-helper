<?php

namespace Modules\Reports\Repositories;

use Esa\Helper\Enums\OrderStatus;
use Illuminate\Support\Facades\DB;

class ReportRepository
{
    public function getOrderCount($start, $end, $status)
    {
        return DB::table('prescription')
            ->whereBetween('UpdatedDate', [$start, $end])
            ->where('Status', $status)
            ->count();
    }

    public function getShippedFridgeOrder($start, $end)
    {
        return DB::table('prescription as p')
            ->join('product as pr', 'p.PrescriptionID', '=', 'pr.PrescriptionID')
            ->join('productcode as pc', 'pr.Code', '=', 'pc.Code')
            ->where('pc.Fridge', 1)
            ->whereBetween('p.UpdatedDate', [$start, $end])
            ->where('p.Status', OrderStatus::SHIPPED->value)
            ->count();
    }

    public function getInventoryUnit($start, $end)
    {
        return DB::table("InventoryItem")
            ->selectRaw("COUNT(ProductCodeID) AS 'unit'")
            ->where('Quantity', '>', 0)
            ->whereIn('Status', ['STORED', 'SPLIT'])
            ->where(function ($query) use ($start, $end) {
                $query->whereNull('DeletedAt')
                    ->orWhereNotBetween('DeletedAt', [$start, $end]);
            })
            ->whereBetween('CreatedAt', [$start, $end])
            ->where(function ($query) use ($start, $end) {
                $query->whereNull('ProcessedAt')
                    ->orWhereNotBetween('ProcessedAt', [$start, $end]);
            })
            ->value('unit');
    }

    public function getInventoryValue($start, $end)
    {
        return DB::table("InventoryItem")
            ->selectRaw("SUM(CurrentItemPrice) AS 'value'")
            ->where('Quantity', '>', 0)
            ->whereIn('Status', ['STORED', 'SPLIT'])
            ->where(function ($query) use ($start, $end) {
                $query->whereNull('DeletedAt')
                    ->orWhereNotBetween('DeletedAt', [$start, $end]);
            })
            ->whereBetween('CreatedAt', [$start, $end])
            ->where(function ($query) use ($start, $end) {
                $query->whereNull('ProcessedAt')
                    ->orWhereNotBetween('ProcessedAt', [$start, $end]);
            })
            ->value('value') ?? 0;
    }

    public function getBatchData($start, $end)
    {
        return DB::table('inventoryitembatch')
            ->selectRaw("count(InventoryItemBatchID) as 'batch_count', sum(Quantity) as 'batch_items'")
            ->where('Status', 'FINISHED')
            ->whereBetween('UpdatedAt', [$start, $end])
            ->first();
    }

    public function getDoopData($start, $end, $type)
    {
        return DB::table('inventory_logs as l')
            ->join('inventoryitem as i', 'l.loggable_id', '=', 'i.InventoryItemID')
            ->selectRaw("count(i.InventoryItemID) as 'doop_count', sum(i.CurrentItemPrice) as 'doop_value'")
            ->whereBetween('l.updated_at', [$start, $end])
            ->where('l.type', $type)
            ->first();
    }
}
