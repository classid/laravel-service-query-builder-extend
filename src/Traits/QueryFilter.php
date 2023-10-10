<?php

namespace Classid\LaravelServiceQueryBuilderExtend\Traits;

use Classid\LaravelServiceQueryBuilderExtend\Contracts\Abstracts\BaseQueryBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

trait QueryFilter
{
    private static string $defaultOperator = "=";
    private array $requestQueryParam;
    private array $filterableColumns;
    private array $relationFilterableColumns;

    /**
     * @param array|null $filterableColumns
     * @param array|null $relationFilterableColumns
     * @return BaseQueryBuilder|QueryFilter
     */
    public function _filterColumn(?array $filterableColumns = null, ?array $relationFilterableColumns = null): self
    {
        $this->setRequestQueryParam()
            ->setFilterableColumns($filterableColumns)
            ->setRelationFilterableColumns($relationFilterableColumns)
            ->defaultFilterableColumn()
            ->filterMainModel()
            ->filterRelationModel();

        return $this;
    }

    /**
     * @return BaseQueryBuilder|QueryFilter
     */
    private function filterMainModel(): self
    {
        // filter column for main model
        foreach (array_intersect_key($this->requestQueryParam, $this->filterableColumns) as $requestedKey => $value) {

            $dbOperator = $this->getDBOperator($requestedKey);
            $dbColumnName = $this->getDBColumn($requestedKey);

            // which mean the request value is only 1
            if (is_string($value)) {
                $this->checkLikeOperator($dbOperator, $value);
                $this->builder->where($dbColumnName, $dbOperator, $value);
            }

            // which mean the request value is more than one
            if (is_array($value)) {
                foreach ($value as $subValue) {
                    $this->checkLikeOperator($dbColumnName, $subValue);
                    $this->builder->orWhere($dbColumnName, $dbOperator, $subValue);
                }
            }
        }

        return $this;
    }

    /**
     *  Description
     *
     *  request query param is come from http request
     *
     *  example postman
     *       filter[name]    =   "budi"
     *       filter[category]=   "food"
     *
     *  the request query format would be :
     *       "filter" :[
     *           "name"      : "budi",
     *           "category"  : "food"
     *       ]
     *
     *  filterable columns are list of column that allow to filter,
     *  it contains array key and value,
     *  key is represent array key on request, for this example is name and category
     *  value is represent for column name on db
     *       [
     *           "name" : "roles.name",
     *           "category" : "roles.category",
     *       ]
     *  it's possible to use different name for request key, as long as same to request key
     *
     *  array_intersect_key will filter request query param that does not allow by filterablecolumns
     *
     * @return void
     */
    private function filterRelationModel(): void
    {
        // looping for every relation
        foreach ($this->relationFilterableColumns as $relationName => $filterableColumns) {
            $intersectedFilter = array_intersect_key($this->requestQueryParam, $filterableColumns);

            // looping for every column on relation
            foreach ($intersectedFilter as $requestedKey => $requestValue) {
                $dbOperator = $this->getDBOperator($requestedKey);
                $dbColumnName = $this->getRelationDBColumn($relationName, $requestedKey);


                if (is_string($requestValue)) {
                    $this->checkLikeOperator($dbOperator, $requestValue);

                    $this->builder->whereHas($relationName, function ($query) use ($dbColumnName, $dbOperator, $requestValue) {
                        $query->where($dbColumnName, $dbOperator, $requestValue);
                    });
                }

                if (is_array($requestValue)) {
                    $dbOperator = ">=";
                    $count = 1;

                    if (isset($filterableColumns[$requestedKey]["behavior"]) && strtolower($filterableColumns[$requestedKey]["behavior"]) === "and") {
                        $dbOperator = "=";
                        $count = count($requestValue);
                    }

                    $this->builder->whereHas($relationName, function ($query) use ($requestValue, $dbColumnName) {
                        $query->whereIn($dbColumnName, $requestValue);
                    }, $dbOperator, $count);
                }
            }
        }
    }


    /**
     * @return BaseQueryBuilder|QueryFilter
     */
    private function defaultFilterableColumn(): self
    {
        if ($filterKey = config('queryextend.filter_query_param_root')) {
            $requestCreatedStartFilter = empty(request()->query()[$filterKey]["created_at"][0]);
            $requestCreatedEndFilter = empty(request()->query()[$filterKey]["created_at"][1]);
        } else {
            $requestCreatedStartFilter = empty(request()->query("filter_created_at")[0]);
            $requestCreatedEndFilter = empty(request()->query("filter_created_at")[1]);
        }

        $this->builder->when(!$requestCreatedStartFilter, function (Builder $query) use ($filterKey) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $filterKey ?
                    request()->query()[$filterKey]["created_at"][0] :
                    request()->query("filter_created_at")[0]
                );
            } catch (\Exception $e) {
                if ($filterKey) {
                    throw ValidationException::withMessages(["$filterKey.created_at.0" => 'Date format should be yyyy-mm-dd']);
                } else {
                    throw ValidationException::withMessages(["filter_created_at.0" => 'Date format should be yyyy-mm-dd']);
                }
            }

            $query->where("created_at", ">=", $date->startOfDay());
        });

        $this->builder->when(!$requestCreatedEndFilter, function (Builder $query) use ($filterKey) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $filterKey ?
                    request()->query()[$filterKey]["created_at"][1] :
                    request()->query("filter_created_at")[1]
                );
            } catch (\Exception $e) {
                if ($filterKey) {
                    throw ValidationException::withMessages(["$filterKey.created_at.1" => 'Date format should be yyyy-mm-dd']);
                } else {
                    throw ValidationException::withMessages(["filter_created_at.1" => 'Date format should be yyyy-mm-dd']);
                }
            }

            $query->where("created_at", "<=", $date->endOfDay());
        });


        return $this;
    }

    /**
     * @param string $operator
     * @param string $value
     * @return void
     */
    private function checkLikeOperator(string $operator, string &$value): void
    {
        if (strtolower($operator) === "like") $value = "%$value%";
    }


    /**
     * @param string $requestedKey
     * @return string
     */
    private function getDBOperator(string $requestedKey): string
    {
        $filterableColumns = $this->filterableColumns;
        $dbOperator = self::$defaultOperator;
        if (isset($filterableColumns[$requestedKey]["operator"])) {
            $dbOperator = $filterableColumns[$requestedKey]["operator"];
        }
        return $dbOperator;
    }

    /**
     * @param string $requestedKey
     * @return string
     */
    private function getDBColumn(string $requestedKey): string
    {

        /**
         * this is for $filterableColumns = [
         *  "name" => "users.name"
         * ]
         *
         * which mean that the value is string
         */
        $dbColumnName = $this->filterableColumns[$requestedKey];

        /**
         * this is case when developer want to custom operator (not using default operator '=')
         * this is for $filterableColumns = [
         *      "name" => [
         *          "column" => "users.name",
         *          "operator" => "like" // could be = >= > < <= !=
         *      ]
         * ]
         */
        if (isset($dbColumnName["column"])) {
            $dbColumnName = $dbColumnName["column"];
        }

        return $dbColumnName;
    }

    /**
     * @param string $relationName
     * @param string $requestedKey
     * @return string
     */
    private function getRelationDBColumn(string $relationName, string $requestedKey): string
    {

        /**
         * this is for $filterableColumns = [
         *  "name" => "users.name"
         * ]
         *
         * which mean that the value is string
         */
        $dbColumnName = $this->relationFilterableColumns[$relationName][$requestedKey];

        /**
         * this is case when developer want to custom operator (not using default operator '=')
         * this is for $filterableColumns = [
         *      "name" => [
         *          "column" => "users.name",
         *          "operator" => "like" // could be = >= > < <= !=
         *      ]
         * ]
         */
        if (isset($dbColumnName["column"])) {
            $dbColumnName = $dbColumnName["column"];
        }

        return $dbColumnName;
    }


    /**
     * @return BaseQueryBuilder|QueryFilter
     */
    private function setRequestQueryParam(): self
    {
        $this->requestQueryParam = config("queryextend.filter_query_param_root") ?
            request()->query(config("queryextend.filter_query_param_root"), []) :
            request()->query();

        return $this;
    }

    /**
     * @param array|null $filterableColumn
     * @return BaseQueryBuilder|QueryFilter
     */
    private function setFilterableColumns(?array $filterableColumn): self
    {
        /**
         * this will get filterable columns from param if not null
         * if null, it will get from model property
         * if null as well, it will set to empty array as default
         */
        $this->filterableColumns = $filterableColumn ?? $this->builder->getModel()->filterableColumns ?? [];

        return $this;
    }

    /**
     * @param array|null $relationFilterableColumns
     * @return BaseQueryBuilder|QueryFilter
     */
    private function setRelationFilterableColumns(?array $relationFilterableColumns): self
    {
        $this->relationFilterableColumns = $relationFilterableColumns ?? $this->builder->getModel()->relationFilterableColumns ?? [];

        return $this;
    }
}
