<?php

namespace Modules\Reports\Contracts;

interface ReportContract
{
    public function columns(): array;
    public function fields(): array;
    public function includedJoins(): array;
}
