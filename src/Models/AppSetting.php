<?php

namespace Esa\Helper\Models;


use Esa\Helper\Enums\SettingGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin Model
 */
class AppSetting extends Model
{
    use HasFactory;
    protected $fillable = ['key', 'name', 'value', 'details', 'type', 'order', 'group'];
    public $timestamps = false;

    protected function value(): Attribute
    {
        return Attribute::make(
            get: function ($value): mixed {
                return match ($this->attributes['type']) {
                    'int' => intval($value),
                    'float' => floatval($value),
                    'bool' => boolval($value),
                    'json' => json_decode($value, true),
                    default => $value
                };
            }
        );
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'key' => 'string',
            'name' => 'string',
            'details' => 'string',
            'type' => 'string',
            'order' => 'int',
            'group' => SettingGroup::class
        ];
    }

    public function scopeGroup(Builder $query, string $group): void
    {
        $query->where('group', $group);
    }

    public function scopeByKey(Builder $query, string $key)
    {
        return $query->where('key', $key)->first();
    }

    public function scopeValueByKey(Builder $query, string $key)
    {
        return $query->select(['type', 'value'])->where('key', $key)->first()->value;
    }
}
