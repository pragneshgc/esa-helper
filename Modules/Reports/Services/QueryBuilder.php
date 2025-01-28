<?php

namespace Modules\Reports\Services;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Reports\Enums\FilterType;
use Illuminate\Database\Query\Builder;


class QueryBuilder
{
    private Builder $builder;

    private array $tableClasses = [];
    private array $fields = [];
    private array $joins = [];

    private array $relationTables = [];
    private array $reportFields = [];

    private array $enumFields = [];

    private string $primaryTable;
    private array $fieldsRefs = [];

    private array $filters = [];
    private array $reportFilters = [];
    public function __construct(protected array $registerClasses, protected Request $request)
    {
        foreach ($this->registerClasses as $key => $reportClass) {
            $class = new $reportClass;
            $this->tableClasses[$class->table] = $reportClass;
            $this->fields[$class->table] = $class->fields();
            $this->joins[$class->table] = $class->includedJoins();
            $this->filters[$class->table] = $class->filters();
        }
    }

    public function get()
    {
        $fields = $this->request->fields;
        $this->reportFilters = $this->recordFilter($fields);

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
        $this->filterRecords();

        $this->setDropdownRecords();

        $records = $this->builder->paginate($this->request->limit);
        if (!empty($this->enumFields)) {
            $records->getCollection()->transform(function ($item) {
                foreach ($item as $k => $i) {
                    if (in_array($k, array_keys($this->enumFields))) {
                        $reflection = new \ReflectionEnum($this->enumFields[$k]);
                        $cases = $reflection->getCases();
                        $item->$k = $this->getEnumNameFrom($cases, $i);
                    }
                }

                return $item;
            });
        }

        return [
            'records' => $records,
            'filters' => $this->reportFilters
        ];
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
                $this->fieldsRefs[$field['key']] = $field['callback'];
                $this->reportFields[] = $isFieldExit['callback'] . ' as `' . $field['text'] . '`';
            } elseif (isset($isFieldExit['enum'])) {
                $this->enumFields[$field['text']] = $field['enum'];
                $this->fieldsRefs[$field['key']] = $field['key'];
                $this->reportFields[] = $isFieldExit['key'] . ' as `' . $field['text'] . '`';
            } else {
                $this->fieldsRefs[$field['key']] = $field['key'];
                $this->reportFields[] = $field['key'] . ' as `' . $field['text'] . '`';
            }
        } else {
            [$tablename, $column] = explode('.', $field['key']);
            if ($this->joins[$this->primaryTable][$tablename]) {
                $this->fieldsRefs[$field['key']] = $field['key'];
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

    private function getEnumNameFrom($cases, $value)
    {
        foreach ($cases as $case) {
            if ($case->getValue()->value == $value) {
                return $case->getValue()->name;
            }
        }
        return '';
    }

    private function filterRecords()
    {
        $filters = json_decode($this->request->f);
        if (!empty($filters)) {
            foreach ($filters as $filter => $value) {
                $recordFilter = $this->reportFilters[$filter];
                if (!empty($value)) {
                    if ($recordFilter['operator'] == 'LIKE') {
                        $value = '%' . $value . '%';
                    }
                    $this->builder->where($recordFilter['key'], $recordFilter['operator'], $value);
                    $this->reportFilters[$filter]['value'] = $value;
                }
            }
        }
    }

    private function recordFilter($fields)
    {
        $filters = [];
        if (!empty($fields)) {
            foreach ($fields as $field) {
                $key = $field['key'];
                [$tablename, $column] = explode('.', $key);
                if (isset($this->filters[$tablename][$key])) {
                    $filters[$key] = [...$this->filters[$tablename][$key], 'column' => $column, 'key' => $key, 'value' => ''];
                }
            }
        }
        return $filters;
    }

    private function setDropdownRecords()
    {
        if (!empty($this->reportFilters)) {
            foreach ($this->reportFilters as $key => $filter) {
                if ($filter['type'] == FilterType::DROPDOWN->value) {
                    $sql = $this->builder->clone();
                    $sql->wheres = [];
                    $sql->bindings['where'] = [];

                    $sql->select($filter['key']);
                    $sql->groupBy(groups: $filter['key']);
                    $this->reportFilters[$key]['values'] = $sql->get();
                }
            }
        }
    }
}
