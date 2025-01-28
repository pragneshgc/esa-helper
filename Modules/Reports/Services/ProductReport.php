<?php

namespace Modules\Reports\Services;

use Modules\Reports\Enums\FilterType;
use Modules\Reports\Enums\SortDirection;
use Modules\Reports\Enums\FilterOperator;
use Modules\Reports\Contracts\ReportContract;


class ProductReport implements ReportContract
{
    public string $table = 'Product';
    public string $reportName = 'Products';
    public string $orderBy = 'Product.ProductID';
    public string $orderDirection = SortDirection::DESCENDING;

    public function fields(): array
    {
        return [
            [
                'key' => 'Product.Code',
                'text' => 'Product.Code'
            ],
            [
                'key' => 'Product.Description',
                'text' => 'Product.Name'
            ],
            [
                'key' => 'Product.Quantity',
                'text' => 'Product.Quantity'
            ],
            [
                'key' => 'Product.Unit',
                'text' => 'Product.Unit'
            ],
            [
                'key' => 'Product.Dosage',
                'text' => 'Product.Dosage'
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

    public function filters(): array
    {
        return [
            'Product.Code' => [
                'operator' => FilterOperator::LIKE->value,
                'type' => FilterType::TEXT->value
            ],
            'Product.Description' => [
                'operator' => FilterOperator::LIKE->value,
                'type' => FilterType::TEXT->value
            ],
            'Product.Quantity' => [
                'operator' => FilterOperator::EQUAL->value,
                'type' => FilterType::TEXT->value
            ],
            'Product.Unit' => [
                'operator' => FilterOperator::EQUAL->value,
                'type' => FilterType::DROPDOWN->value
            ],
            'Product.Dosage' => [
                'operator' => FilterOperator::EQUAL->value,
                'type' => FilterType::TEXT->value
            ],
        ];
    }
}
