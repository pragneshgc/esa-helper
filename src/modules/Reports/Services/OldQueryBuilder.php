<?php

namespace App\ReportsBuilder;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\ReportsBuilder\Contracts\ReportContract;
use Illuminate\Http\Request;

class OldQueryBuilder
{
    private Builder $builder;
    private array $joins = [];

    private string $orderBy;
    private string $orderDirection;

    private $reportObject;
    public function __construct(protected array $registerClasses, protected Request $request)
    {
        dd($registerClasses);
    }

    public static function for(array $classes, Request $request)
    {
        return new static($classes, $request);
    }

    /* public static function for(string $report, $request)
    {
        return new static($report, $request);
    } */

    public function query(): self
    {
        $this->reportObject = new $this->reportClass;
        $reflectionClass = new \ReflectionClass($this->reportObject);

        $reflectionMethod = $reflectionClass->getMethod('includedJoins');
        $this->joins = $reflectionMethod->invoke($this->reportObject);

        $reflectionProperty = $reflectionClass->getProperty('model');
        $modelClass = $reflectionProperty->getValue($this->reportObject);
        $modelObject = new $modelClass;

        $reflectionPropertyOrderBy = $reflectionClass->getProperty('orderBy');
        $this->orderBy = $reflectionPropertyOrderBy->getValue($this->reportObject);

        $reflectionPropertyOrderDirection = $reflectionClass->getProperty('orderDirection');
        $this->orderDirection = $reflectionPropertyOrderDirection->getValue($this->reportObject);

        if (is_subclass_of($modelObject, Model::class)) {
            $this->builder = $modelObject::query();
        }

        return $this;
    }

    private function includeFields(): void
    {
        $fields = $this->request->fields;

        /* foreach ($fields as $field) {
            [$tablename, $column] = explode('.', $field['key']);
            $this->reportTables[] = $tablename;
            $this->reportColumns[] = $field['key'] . ' as `' . $field['text'] . '`';
            $this->reportHeaders[] = $field['text'];
        } */

        if (!empty($fields)) {
            foreach ($fields as $column) {
                $this->builder->addSelect(DB::raw($column));
            }
        }
    }

    private function includeJoins(): void
    {
        if (!empty($this->joins)) {
            foreach ($this->joins as $table => $join) {
                if (in_array($table, $relations)) {
                    $this->builder->join($table, $join[0], $join[1], $join[2]);
                }
            }
        }
    }

    public function get()
    {
        $this->setQueryOrder();
        $this->includeFields();
        $this->includeJoins();

        return $this->builder
            ->orderBy($this->orderBy, $this->orderDirection)
            ->paginate($this->request->limit);
    }

    public function dump()
    {
        $this->builder->dd();
    }

    private function getLimit()
    {
        return $this->request->page * $this->request->limit;
    }

    private function setQueryOrder()
    {
        if (!empty($this->request->orderBy)) {
            $this->orderBy = $this->request->orderBy;
        }

        if (!empty($this->request->orderDirection)) {
            $this->orderDirection = $this->request->orderDirection;
        }
    }
}
