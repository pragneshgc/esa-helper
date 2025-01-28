<?php

namespace Modules\Reports\Enums;

enum FilterType: string
{
    case DATE = 'date';
    case DROPDOWN = 'dropdown';
    case TEXT = 'text';
    case TEXTAREA = 'textarea';
}
