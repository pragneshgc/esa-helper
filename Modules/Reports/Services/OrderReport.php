<?php

namespace Modules\Reports\Services;

use Esa\Helper\Enums\Courier;
use Esa\Helper\Enums\OrderStatus;
use Modules\Reports\Enums\SortDirection;
use Modules\Reports\Contracts\ReportContract;


class OrderReport implements ReportContract
{
    public string $table = 'Prescription';
    public string $reportName = 'Orders';
    public string $orderBy = 'Prescription.PrescriptionID';
    public string $orderDirection = SortDirection::DESCENDING;

    public function fields(): array
    {
        return [
            [
                'key' => 'Prescription.PrescriptionID',
                'text' => 'OrderID'
            ],
            [
                'key' => 'Prescription.DoctorName',
                'text' => 'PrescriberName'
            ],
            [
                'key' => 'Prescription.Email',
                'text' => 'Patient.Email'
            ],
            [
                'key' => 'Prescription.ReferenceNumber',
                'text' => 'ReferenceNumber'
            ],
            [
                'key' => 'Prescription.Sex',
                'text' => 'Patient.Gender'
            ],
            [
                'key' => 'Prescription.Name',
                'text' => 'Patient.Name',
                'callback' => $this->patientName()
            ],
            [
                'key' => 'Prescription.DOB',
                'text' => 'Patient.DOB'
            ],
            [
                'key' => 'Prescription.home-address',
                'text' => 'Patient.HomeAddress',
                'callback' => $this->patientHomeAddress()
            ],
            [
                'key' => 'Prescription.delivery-address',
                'text' => 'Patient.DeliveryAddress',
                'callback' => $this->patientDeliveryAddress()
            ],
            [
                'key' => 'Prescription.Status',
                'text' => 'Order.Status',
                'enum' => OrderStatus::class
            ],
            [
                'key' => 'Product.Description',
                'text' => 'Order.Product'
            ],
            [
                'key' => 'Client.CompanyName',
                'text' => 'Order.Client'
            ],
            [
                'key' => 'Prescription.DeliveryID',
                'text' => 'Order.Courier',
                'enum' => Courier::class
            ],
        ];
    }

    public function columns(): array
    {
        return [
            'fields' => $this->fields()
        ];
    }

    public function includedJoins(): array
    {
        return [
            'Activity' => ['Activity.OrderID', '=', 'Prescription.PrescriptionID'],
            'Product' => ['Product.PrescriptionID', '=', 'Prescription.PrescriptionID'],
            'Client' => ['Client.ClientID', '=', 'Prescription.ClientID']
        ];
    }

    private function patientDeliveryAddress()
    {
        return "CONCAT(Prescription.DAddress1, ', ',  Prescription.DAddress2, ', ', Prescription.DAddress3, ', ',  Prescription.DAddress4,  ', ', Prescription.DPostcode, ', ',(SELECT `Name` FROM country WHERE CountryID = Prescription.DCountryCode))";
    }
    private function patientHomeAddress()
    {
        return "CONCAT(Prescription.Address1, ', ',  Prescription.Address2, ', ', Prescription.Address3, ', ',  Prescription.Address4,  ', ', Prescription.Postcode, ', ',(SELECT `Name` FROM country WHERE CountryID = Prescription.CountryCode))";
    }

    private function patientName(): string
    {
        return "CONCAT(Prescription.Name, ' ',  Prescription.Surname)";
    }
}
