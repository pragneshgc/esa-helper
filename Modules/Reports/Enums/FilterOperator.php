<?php

namespace Modules\Reports\Enums;

use ValueError;
use Esa\Helper\Traits\EnumToArray;

enum FilterOperator: string
{
    case EQUAL = '=';
    case NOT_EQUAL = '!=';
    case IN = 'IN';
    case NOT_IN = 'NOT IN';
    case GREATER_THAN = '>';
    case GREATER_THAN_OR_EQUAL = '>=';
    case LESS_THAN = '<';
    case LESS_THAN_OR_EQUAL = '<=';
    case IS_NULL = 'IS NULL';
    case IS_NOT_NULL = 'IS NOT NULL';
    case BETWEEN = 'BETWEEN';
    case NOT_BETWEEN = 'NOT BETWEEN';
    case LIKE = 'LIKE';
    case NOT_LIKE = 'NOT LIKE';

    public static function fromName(string $name): string
    {
        foreach (self::cases() as $status) {
            if ($name === $status->name) {
                return $status->value;
            }
        }
        throw new ValueError("$name is not a valid backing value for enum " . self::class);
    }
}
