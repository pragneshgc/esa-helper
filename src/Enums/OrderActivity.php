<?php

namespace Esa\Helper\Enums;

use Esa\Helper\Traits\EnumToArray;

enum OrderActivity: int
{
    use EnumToArray;
        //pharmacy activities
    case ORDER_RECEIVED = 1000;
    case ORDER_STATUS_CHANGED = 1100;
    case ORDER_DETAIL_UPDATED = 1200;
    case REVERT_ORDER_DETAIL_UPDATED = 1300;
    case PRINTED_DELIVERY_NOTE = 1400;
    case PRINTED_PHARMACY_LABEL = 1500;
    case PRESCRIPTION_ATTACHED = 1600;
    case ORDER_RESEND = 1700;

        //inventory activities
    case PRODUCT_ATTACHED_TO_ORDER = 2000;
    case POUCH_PRODUCT_ATTACHED_TO_ORDER = 2001;
    case PRODUCT_ATTACHED_TO_ORDER_FOR_PARTIAL_RESEND = 2002;
    case PRODUCT_ATTACHED_TO_ORDER_VIA_OVERRIDE = 2003;
    case PRODUCT_ATTACHED_TO_ORDER_WITH_SPLIT_CREATION = 2004;
    case SPLIT_PRODUCT_ATTACHED_TO_ORDER = 2005;

    case PRODUCT_UNATTACHED_FROM_ORDER = 2100;
    case PRODUCT_UNATTACHED_FROM_ORDER_AND_DESTROYED = 2101;
    case PRODUCT_UNATTACHED_FROM_ORDER_AND_CANISTER_REFILLED = 2102;

    case ORDER_DECOMISSION = 2200;
    case ORDER_PACKED_AND_DECOMISSION = 2201;

    case ITEM_RECOMISSIONED = 2300;
    case ORDER_RECOMISSIONED = 2301;
    case ORDER_PARTIALLY_RECOMISSIONED = 2302;

    case OVERRIDE_SCAN_PRODUCT_NOT_SENT_ON_PATIENT_REQUEST = 2400;
    case OVERRIDE_AUTHORIZED = 2500;
    case ACTIVITY_REVERTED = 2600;
    case ORDER_RESENT_WITH_REASON = 2700;

        //shipping activities
    case REQUEST_LABEL = 3000;
    case PRINT_LABEL = 3100;
    case LOG_REPRINT = 3200;
    case MANUAL_PRINT = 3300;
    case PRINT_ERROR = 3400;

        //deprecated
    case OCR_FILE_TRANSFERED = 900;
    case PRINTED_FILE = 901;
    case DOWNLOAD_FILE = 902;
    case OTHER = 1;

    public function log(?string ...$log): string
    {
        return match ($this) {
            //pharmacy activities
            self::ORDER_RECEIVED => "Order Received",
            self::ORDER_STATUS_CHANGED => sprintf("Order changed to %s", $log[0]),
            self::ORDER_DETAIL_UPDATED => "Updated order details",
            self::REVERT_ORDER_DETAIL_UPDATED => "Reverted update on order details",
            self::PRINTED_DELIVERY_NOTE => "printed Delivery Note",
            self::PRINTED_PHARMACY_LABEL => "printed Pharmacy Label",
            self::PRESCRIPTION_ATTACHED => "Prescription attached",
            self::ORDER_RESEND => sprintf("Order Resend - Reason: %s", $log[0]),

            //inventory activities
            self::PRODUCT_ATTACHED_TO_ORDER => sprintf("product %s attached to order", $log[0]),
            self::POUCH_PRODUCT_ATTACHED_TO_ORDER => sprintf("pouch of %s attached to order", $log[0]),
            self::PRODUCT_ATTACHED_TO_ORDER_FOR_PARTIAL_RESEND => sprintf("product %s attached to order for a partial resend", $log[0]),
            self::PRODUCT_ATTACHED_TO_ORDER_VIA_OVERRIDE => sprintf("product %s attached to order via override", $log[0]),
            self::PRODUCT_ATTACHED_TO_ORDER_WITH_SPLIT_CREATION => sprintf("product %s attached to order with split creation", $log[0]),
            self::SPLIT_PRODUCT_ATTACHED_TO_ORDER => sprintf("split product %s attached to order", $log[0]),

            self::PRODUCT_UNATTACHED_FROM_ORDER => sprintf("product %s unattached from order", $log[0]),
            self::PRODUCT_UNATTACHED_FROM_ORDER_AND_DESTROYED => sprintf("product %s unattached from order and destroyed", $log[0]),
            self::PRODUCT_UNATTACHED_FROM_ORDER_AND_CANISTER_REFILLED => sprintf("product %s unattached from order and canister refilled", $log[0]),

            self::ORDER_DECOMISSION => "order decomissioned",
            self::ORDER_PACKED_AND_DECOMISSION => "order packed and decomissioned",

            self::ITEM_RECOMISSIONED => sprintf("item recomissioned with reason: \"%s\"", $log[0]),
            self::ORDER_RECOMISSIONED => sprintf("order recomissioned with reason: \"%s\"", $log[0]),
            self::ORDER_PARTIALLY_RECOMISSIONED => sprintf("order partially recomissioned with reason: \"%s\"", $log[0]),

            self::OVERRIDE_SCAN_PRODUCT_NOT_SENT_ON_PATIENT_REQUEST => "Override Scan - Product not sent on patient request",
            self::OVERRIDE_AUTHORIZED => sprintf("override authorized by %s", $log[0]),
            self::ACTIVITY_REVERTED => sprintf("Activity \"%s\" made by %s reverted", $log[0], $log[1]),
            self::ORDER_RESENT_WITH_REASON => sprintf("order resent with reason: \"%s\"", $log[0]),

            //shipping activities
            self::REQUEST_LABEL => sprintf("%s request label", $log[0]),
            self::PRINT_LABEL => sprintf("printed %s Label", $log[0]),
            self::LOG_REPRINT => join(",", $log),
            self::MANUAL_PRINT => sprintf("%s manual print", $log[0]),
            self::PRINT_ERROR => join(",", $log),

            //deprecated
            self::OCR_FILE_TRANSFERED => "OCS file transfered for machine processing",
            self::PRINTED_FILE => sprintf("printed %s CSV", $log[0]),
            self::DOWNLOAD_FILE => sprintf("downloaded %s CSV", $log[0]),
            self::OTHER => join(",", $log),
            default => join(",", $log)
        };
    }
}
