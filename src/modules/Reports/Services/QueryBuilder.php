<?php

namespace Esa\Helper\Modules\Reports\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;


class QueryBuilder
{
    private Builder $builder;

    private array $tableClasses = [];
    private array $fields = [];
    private array $joins = [];

    private array $relationTables = [];
    private array $reportFields = [];

    private string $primaryTable;

    public function __construct(protected array $registerClasses, protected Request $request)
    {
        foreach ($this->registerClasses as $key => $reportClass) {
            $class = new $reportClass;
            $this->tableClasses[$class->table] = $reportClass;
            $this->fields[$class->table] = $class->fields();
            $this->joins[$class->table] = $class->includedJoins();
        }
    }

    public function get()
    {
        $fields = $this->request->fields;
        foreach ($fields as $key => $field) {
            [$tablename, $column] = explode('.', $field['key']);
            if ($key == 0) {
                $this->primaryTable = $tablename;
            } else {
                $this->relationTables[] = $tablename;
            }

            $this->validateTableFields($tablename, $field);
        }

        $this->relationTables = collect(array_unique($this->relationTables))->diff([$this->primaryTable])->toArray();

        $this->getQueryBuilder();
        $this->includeFields();
        $this->setQueryOrder();
        $this->includeJoins();

        $this->builder->limit($this->request->limit);
        echo $this->builder->toRawSql();
        exit;
    }

    private function getQueryBuilder()
    {
        $this->builder = DB::table($this->primaryTable);
    }
    private function validateTableFields($tablename, $field)
    {
        if (isset($this->fields[$tablename])) {
            $isFieldExit = collect($this->fields[$tablename])->where('key', $field['key'])->first();
            if (isset($isFieldExit['callback'])) {
                $this->reportFields[] = $isFieldExit['callback'] . ' as `' . $field['text'] . '`';
            } else {
                $this->reportFields[] = $field['key'] . ' as `' . $field['text'] . '`';
            }
        } else {
            [$tablename, $column] = explode('.', $field['key']);
            if ($this->joins[$this->primaryTable][$tablename]) {
                $this->reportFields[] = $field['key'] . ' as `' . $field['text'] . '`';
            }
        }
    }

    private function includeFields()
    {
        if (!empty($this->reportFields)) {
            foreach ($this->reportFields as $column) {
                $this->builder->addSelect(DB::raw($column));
            }
        }
    }

    private function setQueryOrder()
    {
        if (!empty($this->request->orderBy) && !empty($this->request->orderDirection)) {
            $this->builder->orderBy($this->request->orderBy, $this->request->orderDirection);
        } else {
            $this->builder->orderBy($this->request->fields[0]['key'], 'desc');
        }
    }

    private function includeJoins()
    {
        if (!empty($this->relationTables)) {
            foreach ($this->relationTables as $relation) {
                if (isset($this->joins[$this->primaryTable][$relation])) {
                    $join = $this->joins[$this->primaryTable][$relation];
                    $this->builder->join($relation, $join[0], $join[1], $join[2]);
                }
            }
        }
    }
}
