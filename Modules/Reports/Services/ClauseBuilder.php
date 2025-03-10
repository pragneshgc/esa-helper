<?php

namespace Modules\Reports\Services;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Modules\Reports\Enums\FilterOperator;

class ClauseBuilder
{
    private string $group;
    private string $operator;
    private string $field;
    private $value;

    private array $unCatogoriedOperator = [
        'GREATER_THAN',
        'GREATER_THAN_OR_EQUAL',
        'LESS_THAN',
        'LESS_THAN_OR_EQUAL'
    ];

    private array $arrayValues = [
        'whereBetween',
        'whereNotBetween',
        'orWhereBetween',
        'orWhereNotBetween',
        'whereIn',
        'whereNotIn',
        'orWhereIn',
        'orWhereNotIn'
    ];

    private array $withoutValues = [
        'whereNull',
        'whereNotNull',
        'orWhereNull',
        'orWhereNotNull'
    ];

    private array $likeOperator = [
        'whereLike',
        'whereNotLike',
        'orWhereLike',
        'orWhereNotLike'
    ];
    public function __construct(private Builder $builder, private array $fields) {}

    public function setOperator(string $operator, string $group = 'OR'): self
    {
        if ($group == 'OR') {
            $this->operator = match ($operator) {
                'EQUAL' => 'orWhere',
                'NOT_EQUAL' => 'orWhereNot',
                'LIKE' => 'orWhereLike',
                'NOT_LIKE' => 'orWhereNotLike',
                'IN' => 'orWhereIn',
                'NOT_IN' => 'orWhereNotIn',
                'IS_NULL' => 'orWhereNull',
                'IS_NOT_NULL' => 'orWhereNotNull',
                'BETWEEN' => 'orWhereBetween',
                'NOT_BETWEEN' => 'orWhereNotBetween',
                'WHERE_DATE' => 'orWhereDate',
                'WHERE_DATE_BETWEEN' => 'orWhereBetween',
                'WHERE_DATETIME' => 'orWhere',
                'WHERE_DATETIME_BETWEEN' => 'orWhereBetween',
                default => $operator,
            };
        } else {
            $this->operator = match ($operator) {
                'EQUAL' => 'where',
                'NOT_EQUAL' => 'whereNot',
                'LIKE' => 'whereLike',
                'NOT_LIKE' => 'whereNotLike',
                'IN' => 'whereIn',
                'NOT_IN' => 'whereNotIn',
                'IS_NULL' => 'whereNull',
                'IS_NOT_NULL' => 'whereNotNull',
                'BETWEEN' => 'whereBetween',
                'NOT_BETWEEN' => 'whereNotBetween',
                'WHERE_DATE' => 'whereDate',
                'WHERE_DATE_BETWEEN' => 'whereBetween',
                'WHERE_DATETIME' => 'where',
                'WHERE_DATETIME_BETWEEN' => 'whereBetween',
                default => $operator,
            };
        }
        return $this;
    }

    public function setFieldValue(string $field, string|array $value)
    {
        if (isset($this->fields[$field]['dateFormat'])) {
            if (is_array($value)) {
                $dateformat = $this->fields[$field]['dateFormat'];
                $value = array_map(function ($val) use ($dateformat) {
                    return Carbon::parse($val)->format($dateformat);
                }, $value);
            }
        }
        $this->value = $value;
        if (in_array($this->operator, $this->arrayValues)) {
            if (is_string($this->value)) {
                $this->value = explode(',', $this->value);
            }
        }
        if (in_array($this->operator, $this->likeOperator)) {
            $this->value = "%" . $this->value . "%";
        }
        $this->field = $field;
        $this->value = $value;
        return $this;
    }

    public function get()
    {
        if (in_array($this->operator, $this->unCatogoriedOperator)) {
            $this->builder->where($this->field, FilterOperator::fromName($this->operator), $this->value);
        } else if (in_array($this->operator, $this->withoutValues)) {
            $this->builder->{$this->operator}($this->field);
        } else {
            $this->builder->{$this->operator}($this->field, $this->value);
        }

        return $this->builder;
    }
}
