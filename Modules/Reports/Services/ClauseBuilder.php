<?php

namespace Modules\Reports\Services;

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
    public function __construct(private Builder $builder) {}

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
                default => $operator,
            };
        }
        return $this;
    }
    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }
    public function setField(string $field): self
    {
        $this->field = $field;
        return $this;
    }

    public function get()
    {
        if (in_array($this->operator, $this->arrayValues)) {
            $this->value = explode(',', $this->value);
        }
        if (in_array($this->operator, $this->likeOperator)) {
            $this->value = "%" . $this->value . "%";
        }

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
