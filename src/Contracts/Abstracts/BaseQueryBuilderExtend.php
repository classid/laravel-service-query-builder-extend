<?php

namespace Classid\LaravelServiceQueryBuilderExtend\Contracts\Abstracts;

use Classid\LaravelServiceQueryBuilderExtend\Traits\QueryFilter;
use Classid\LaravelServiceQueryBuilderExtend\Traits\QueryOrder;
use Closure;
use DateTimeInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Validation\ValidationException;

class BaseQueryBuilderExtend
{
    use QueryOrder {
        QueryOrder::_orderColumn as private orderColumnTrait;
    }
    use QueryFilter {
        QueryFilter::_filterColumn as private filterColumnTrait;
    }

    public Builder $builder;
    public BaseQueryBuilder $baseQueryBuilder;

    public function __construct(BaseQueryBuilder $baseQueryBuilder)
    {
        $this->baseQueryBuilder = $baseQueryBuilder;
        $this->builder = $baseQueryBuilder->builder;
    }

    /**
     * @param $name
     * @param $arguments
     * @return BaseQueryBuilder
     */
    public function forwardScope($name, $arguments): BaseQueryBuilder
    {
        $this->builder->getModel()->$name($this->builder, ...$arguments);
        return $this->baseQueryBuilder;
    }

    /**
     * @param array|null $filterableColumns
     * @param array|null $relationFilterableColumns
     * @return BaseQueryBuilder
     * @throws ValidationException
     */
    public function filterColumn(?array $filterableColumns = null, ?array $relationFilterableColumns = null): BaseQueryBuilder
    {
        $this->filterColumnTrait($filterableColumns, $relationFilterableColumns);
        $this->baseQueryBuilder->applyFilterParams();

        return $this->baseQueryBuilder;
    }

    /**
     * @param array|string|null $orderableColumns
     * @param string $direction
     * @return BaseQueryBuilder
     */
    public function orderColumn(array|string|null $orderableColumns = null, string $direction = "ASC"): BaseQueryBuilder
    {
        $this->orderColumnTrait($orderableColumns, $direction);
        return $this->baseQueryBuilder;
    }

    /**
     * @return Collection|null
     */
    public function get(): Collection|null
    {
        return $this->builder->get();
    }

    /**
     * @param array $requestedData
     * @return int
     */
    public function update(array $requestedData): int
    {
        return $this->builder->update($requestedData);
    }

    /**
     * @return int
     */
    public function delete(): int
    {
        return $this->builder->delete();
    }

    /**
     * @param array|string $relations
     * @return BaseQueryBuilder
     */
    public function with(array|string $relations): BaseQueryBuilder
    {
        $this->builder->with($relations);
        return $this->baseQueryBuilder;
    }


    /**
     * @param array|string $relations
     * @return BaseQueryBuilder
     */
    public function without(array|string $relations): BaseQueryBuilder
    {
        $this->builder->without($relations);
        return $this->baseQueryBuilder;
    }

    /**
     * @param array|string $relation
     * @param string $column
     * @return BaseQueryBuilder
     */
    public function withAvg(array|string $relation, string $column): BaseQueryBuilder
    {
        $this->builder->withAvg($relation, $column);
        return $this->baseQueryBuilder;
    }


    /**
     * @param mixed $relations
     * @return BaseQueryBuilder
     */
    public function withCount(mixed $relations): BaseQueryBuilder
    {
        $this->builder->withCount($relations);
        return $this->baseQueryBuilder;
    }


    /**
     * @param array|string $relation
     * @param string $column
     * @return BaseQueryBuilder
     */
    public function withMin(array|string $relation, string $column): BaseQueryBuilder
    {
        $this->builder->withMin($relation, $column);
        return $this->baseQueryBuilder;
    }


    /**
     * @param array|string $relation
     * @param string $column
     * @return BaseQueryBuilder
     */
    public function withMax(array|string $relation, string $column): BaseQueryBuilder
    {
        $this->builder->withMax($relation, $column);
        return $this->baseQueryBuilder;
    }


    /**
     * @param array|string $relation
     * @param string $column
     * @return BaseQueryBuilder
     */
    public function withSum(array|string $relation, string $column): BaseQueryBuilder
    {
        $this->builder->withSum($relation, $column);
        return $this->baseQueryBuilder;
    }

    /**
     * @param Relation|string $relation
     * @param string $operator
     * @param int $count
     * @param string $boolean
     * @param Closure|null $callback
     * @return BaseQueryBuilder
     */
    public function has(Relation|string $relation, string $operator = '>=', int $count = 1, string $boolean = 'and', Closure|null $callback = null): BaseQueryBuilder
    {
        $this->builder->has($relation, $operator, $count, $boolean, $callback);
        return $this->baseQueryBuilder;
    }


    /**
     * @param string $relation
     * @param Closure|null $callback
     * @param string $operator
     * @param int $count
     * @return BaseQueryBuilder
     */
    public function whereHas(string $relation, Closure|null $callback = null, string $operator = '>=', int $count = 1): BaseQueryBuilder
    {
        $this->builder->whereHas($relation, $callback, $operator, $count);
        return $this->baseQueryBuilder;
    }


    /**
     * @param string $relation
     * @param Closure|null $callback
     * @param string $operator
     * @param int $count
     * @return BaseQueryBuilder
     */
    public function orWhereHas(string $relation, Closure|null $callback = null, string $operator = '>=', int $count = 1): BaseQueryBuilder
    {
        $this->builder->orWhereHas($relation, $callback, $operator, $count);
        return $this->baseQueryBuilder;
    }


    /**
     * @param array|string $column
     * @param string|null $operator
     * @param string|null $value
     * @param string|null $boolean
     * @return BaseQueryBuilder
     */
    public function where(array|string $column, ?string $operator = null, ?string $value = null, ?string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->where($column, $operator, $value, $boolean);
        return $this->baseQueryBuilder;
    }


    /**
     * @param array|string $column
     * @param string|null $operator
     * @param string|null $value
     * @return BaseQueryBuilder
     */
    public function orWhere(array|string $column, ?string $operator = null, ?string $value = null): BaseQueryBuilder
    {
        $this->builder->orWhere($column, $operator, $value);
        return $this->baseQueryBuilder;
    }


    /**
     * @param $column
     * @param string|null $operator
     * @param string|null $value
     * @param string|null $boolean
     * @return BaseQueryBuilder
     */
    public function whereNot($column, ?string $operator = null, ?string $value = null, ?string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->whereNot($column, $operator, $value, $boolean);
        return $this->baseQueryBuilder;
    }


    /**
     * @param string $column
     * @param array $values
     * @param string $boolean
     * @param bool $not
     * @return BaseQueryBuilder
     */
    public function whereBetween(string $column, array $values, string $boolean = 'and', bool $not = false): BaseQueryBuilder
    {
        $this->builder->whereBetween($column, $values, $boolean, $not);
        return $this->baseQueryBuilder;
    }


    /**
     * @param string $column
     * @param array|string $values
     * @param string $boolean
     * @return BaseQueryBuilder
     */
    public function whereNotBetween(string $column, array|string $values, string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->whereNotBetween($column, $values, $boolean);
        return $this->baseQueryBuilder;
    }

    /**
     * @param string $column
     * @param array $values
     * @param string $boolean
     * @param bool $not
     * @return BaseQueryBuilder
     */
    public function whereBetweenColumns(string $column, array $values, string $boolean = 'and', bool $not = false): BaseQueryBuilder
    {
        $this->builder->whereBetweenColumns($column, $values, $boolean, $not);
        return $this->baseQueryBuilder;
    }


    /**
     * @param string $column
     * @param array $values
     * @param string $boolean
     * @return BaseQueryBuilder
     */
    public function whereNotBetweenColumns(string $column, array $values, string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->whereNotBetweenColumns($column, $values, $boolean);
        return $this->baseQueryBuilder;
    }

    /**
     * @param string $column
     * @param array $values
     * @param string $boolean
     * @param bool $not
     * @return BaseQueryBuilder
     */
    public function whereIn(string $column, array $values, string $boolean = 'and', bool $not = false): BaseQueryBuilder
    {
        $this->builder->whereIn($column, $values, $boolean, $not);
        return $this->baseQueryBuilder;
    }

    /**
     * @param string $column
     * @param array $values
     * @param string $boolean
     * @return BaseQueryBuilder
     */
    public function whereNotIn(string $column, array $values, string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->whereNotIn($column, $values, $boolean);
        return $this->baseQueryBuilder;
    }

    /**
     * @param array|string $columns
     * @param string $boolean
     * @param bool $not
     * @return BaseQueryBuilder
     */
    public function whereNull(array|string $columns, string $boolean = 'and', bool $not = false): BaseQueryBuilder
    {
        $this->builder->whereNull($columns, $boolean, $not);
        return $this->baseQueryBuilder;
    }


    /**
     * @param string|array $columns
     * @param string $boolean
     * @return BaseQueryBuilder
     */
    public function whereNotNull(string|array $columns, string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->whereNotNull($columns, $boolean);
        return $this->baseQueryBuilder;
    }


    /**
     * @param string $column
     * @param string $operator
     * @param DateTimeInterface|string|null $value
     * @param string $boolean
     * @return BaseQueryBuilder
     */
    public function whereDate(string $column, string $operator, DateTimeInterface|string|null $value = null, string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->whereDate($column, $operator, $value, $boolean);
        return $this->baseQueryBuilder;
    }


    /**
     * @param string $column
     * @param string $operator
     * @param DateTimeInterface|string|null $value
     * @param string $boolean
     * @return BaseQueryBuilder
     */
    public function whereMonth(string $column, string $operator, DateTimeInterface|string|null $value = null, string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->whereMonth($column, $operator, $value, $boolean);
        return $this->baseQueryBuilder;
    }

    /**
     * @param string $column
     * @param string $operator
     * @param DateTimeInterface|string|null $value
     * @param string $boolean
     * @return BaseQueryBuilder
     */
    public function whereDay(string $column, string $operator, DateTimeInterface|string|null $value = null, string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->whereDay($column, $operator, $value, $boolean);
        return $this->baseQueryBuilder;
    }


    /**
     * @param string $column
     * @param string $operator
     * @param DateTimeInterface|string|null $value
     * @param string $boolean
     * @return BaseQueryBuilder
     */
    public function whereYear(string $column, string $operator, DateTimeInterface|string|null $value = null, string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->whereYear($column, $operator, $value, $boolean);
        return $this->baseQueryBuilder;
    }


    /**
     * @param string $column
     * @param string $operator
     * @param DateTimeInterface|string|null $value
     * @param string $boolean
     * @return BaseQueryBuilder
     */
    public function whereTime(string $column, string $operator, DateTimeInterface|string|null $value = null, string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->whereTime($column, $operator, $value, $boolean);
        return $this->baseQueryBuilder;
    }


    /**
     * @param array|string $first
     * @param string|null $operator
     * @param string|null $second
     * @param string|null $boolean
     * @return BaseQueryBuilder
     */
    public function whereColumn(array|string $first, ?string $operator = null, ?string $second = null, ?string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->whereColumn($first, $operator, $second, $boolean);
        return $this->baseQueryBuilder;
    }

    /**
     * @param array|string $first
     * @param string|null $operator
     * @param string|null $second
     * @return BaseQueryBuilder
     */
    public function orWhereColumn(array|string $first, ?string $operator = null, ?string $second = null): BaseQueryBuilder
    {
        $this->builder->orWhereColumn($first, $operator, $second);
        return $this->baseQueryBuilder;
    }

    /**
     * @param array $whereClause
     * @param array $columns
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function getAllDataPaginated(array $whereClause = [], array $columns = ["*"], ?int $perPage = null):LengthAwarePaginator
    {
        if (!$perPage) {
            $perPage = request()->query("perpage", config('queryextend.perpage'));
        }
        return $this->builder
            ->select($columns)
            ->where($whereClause)
            ->paginate($perPage);
    }

    /**
     * @param array $whereClause
     * @param array $columns
     * @return Builder[]|Collection
     */
    public function getAllData(array $whereClause = [], array $columns = ["*"]): Collection|array
    {
        return $this->builder
            ->select($columns)
            ->where($whereClause)
            ->get();
    }

    /**
     * @param string|int|array $id
     * @param array $columns
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function getDataById(string|int|array $id, array $columns = ["*"])
    {
        return $this->builder->select($columns)->find($id);
    }

    /**
     * @param array $whereClause
     * @param array $columns
     * @return Builder|Model|object|null
     */
    public function getSingleData(array $whereClause = [], array $columns = ["*"])
    {
        return $this->builder
            ->select($columns)
            ->where($whereClause)
            ->first();
    }

    /**
     * @param array $requestedData
     * @return Builder|Model
     */
    public function addNewData(array $requestedData)
    {
        return $this->builder->create($requestedData);
    }

    /**
     * Use to add new data bulk
     *
     * @param array $requestedData
     * @return int
     */
    public function addMultipleData(array $requestedData): int
    {
        return $this->builder->insert($requestedData);
    }

    /**
     * @param string|int $id
     * @param array $requestedData
     * @param array $columns
     * @param bool $isReturnObject
     * @return Builder|Builder[]|Collection|Model|int|null
     */
    public function updateDataById(string|int $id, array $requestedData, array $columns = ["*"], bool $isReturnObject = true)
    {
        $updatedData = $this->builder
            ->where("id", $id)
            ->update($requestedData);
        if (!$isReturnObject) return $updatedData;

        return $this->builder->find($id, $columns);
    }


    /**
     * @param array $whereClause
     * @param array $requestedData
     * @param array $columns
     * @param bool $isReturnObject
     * @return Collection|int|null
     */
    public function updateDataByWhereClause(array $whereClause, array $requestedData, array $columns = ["*"], bool $isReturnObject = false)
    {
        $updatedData = $this->builder
            ->where($whereClause)
            ->update($requestedData);
        if (!$isReturnObject) return $updatedData;

        return $this->getAllData($whereClause, $columns);
    }

    /**
     * @param string|int $id
     * @return mixed
     */
    public function deleteDataById(string|int $id)
    {
        return $this->builder
            ->where("id", $id)
            ->delete();
    }

    /**
     * @param array $whereClause
     * @return mixed
     */
    public function deleteDataByWhereClause(array $whereClause)
    {
        return $this->builder
            ->where($whereClause)
            ->delete();
    }
}
