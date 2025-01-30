<?php

namespace Modules\Reports\Enums;

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
    case BETWEEN = 'BETWEEN';
    case LIKE = 'LIKE';
}
