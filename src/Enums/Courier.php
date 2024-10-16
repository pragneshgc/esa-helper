<?php

namespace Esa\Helper\Enums;

use Esa\Helper\Traits\EnumToArray;

enum Courier: string
{
    use EnumToArray;
    case TNT = '3';
    case DPD = '4';
    case ROYALMAIL = '5';
    case UPS = '7';
    case TNT_UK = '8';
    case DHL = '10';
    case POSTNL = '11';

    public function storagePath(): string
    {
        return match ($this) {
            Courier::DPD => 'dpd',
            Courier::ROYALMAIL => 'rml',
            Courier::DHL => 'dhl',
            Courier::POSTNL => 'postnl',
        };
    }

    public function labelExtention(): string
    {
        return match ($this) {
            Courier::DPD => '.zpl',
            Courier::ROYALMAIL => '.pdf',
            Courier::DHL => '.zpl',
            Courier::POSTNL => '.zpl',
        };
    }

    public function logo(): string
    {
        return match ($this) {
            Courier::DPD => 'images/logo/dpd.png',
            Courier::ROYALMAIL => 'images/logo/rmail.png',
            Courier::DHL => 'images/logo/dhl.png',
            Courier::POSTNL => 'images/logo/postnl.svg',
        };
    }

    public static function settings($key): array
    {
        return match ($key) {
            'dpd' => self::dpdSettings(),
            'ups' => self::upsSettings(),
            'rml' => self::rmlSettings(),
            'dhl' => self::dhlSettings(),
            'postnl' => self::postnlSettings(),
        };
    }

    private static function dpdSettings(): array
    {
        return [
            'username' => "",
            'password' => null
        ];
    }

    private static function upsSettings(): array
    {
        return [
            'endpoint' => "",
            'shipperNumber' => "",
            'shipperName' => "",
            'taxIdentificationNumber' => "",
            'licenseNumber' => "",
            'password' => ""
        ];
    }

    private static function rmlSettings(): array
    {
        return [
            'endpoint' => "",
            'token' => null
        ];
    }

    private static function dhlSettings(): array
    {
        return [
            "vat" => "",
            "eori" => "",
            "contents" => "",
            "shipper_name" => "",
            "shipper_email" => "",
            "shipper_postcode" => "",
            "shipper_address_1" => "",
            "shipper_address_2" => "",
            "shipper_address_3" => "",
            "shipper_address_4" => "",
            "shipper_telephone" => "",
            "shipper_country_id" => "",
            "billing_account_number" => "",
            "shipper_account_number" => "",
        ];
    }

    private static function postnlSettings(): array
    {
        return [
            'City' => '',
            'Countrycode' => '',
            'Street' => '',
            'Zipcode' => '',
            'CustomerCode' => '',
            'CustomerNumber' => '',
            'ContactPerson' => '',
            'Name' => '',
            'Email' => ''
        ];
    }
}
