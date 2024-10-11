<?php

namespace Esa\Helper\Enums;

use Esa\Helper\Traits\EnumToArray;

enum SettingGroup: string
{
    use EnumToArray;
    case GENERAL = 'general';
}
