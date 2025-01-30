<?php

namespace Modules\Reports\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicReport extends Model
{
    protected $fillable = ['user_id', 'name', 'fields'];
}
