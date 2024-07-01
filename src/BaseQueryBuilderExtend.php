<?php

namespace Classid\LaravelServiceQueryBuilderExtend;

use Classid\LaravelServiceQueryBuilderExtend\Contracts\Abstracts\BaseQueryBuilder;
use Classid\LaravelServiceQueryBuilderExtend\Traits\QueryFilter;
use Classid\LaravelServiceQueryBuilderExtend\Traits\QueryOrder;
use Closure;
use DateTimeInterface;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Validation\ValidationException;

/**
 * @template T
 */
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
     * @return int
     */
    public static function getPerPage(): int
    {
        return request()->query(config("servicerepo.perpage.key"), config('servicerepo.perpage.value'));
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
     * @return Collection
     */
    public function get(): Collection
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
     * @param Closure|string|null $callback
     * @return BaseQueryBuilder
     */
    public function with(array|string $relations, Closure|null|string $callback = null): BaseQueryBuilder
    {
        if (is_null($callback)) {
            $this->builder->with($relations);
        } else {
            $this->builder->with($relations, $callback);
        }
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
     * @param array|string|Closure|Expression $column
     * @param mixed $operator
     * @param mixed $value
     * @param string $boolean
     * @return BaseQueryBuilder
     */
    public function where(array|string|Closure|Expression $column, mixed $operator = null, mixed $value = null, string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->where($column, $operator, $value, $boolean);
        return $this->baseQueryBuilder;
    }


    /**
     * @param array|Closure|Expression|string $column
     * @param mixed $operator
     * @param mixed $value
     * @return BaseQueryBuilder
     */
    public function orWhere(array|Closure|Expression|string $column, mixed $operator = null, mixed $value = null): BaseQueryBuilder
    {
        $this->builder->orWhere($column, $operator, $value);
        return $this->baseQueryBuilder;
    }


    /**
     * @param array|Closure|Expression|string $column
     * @param mixed $operator
     * @param mixed $value
     * @param string $boolean
     * @return BaseQueryBuilder
     */
    public function whereNot(array|Closure|Expression|string $column, mixed $operator = null, mixed $value = null, string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->whereNot($column, $operator, $value, $boolean);
        return $this->baseQueryBuilder;
    }


    /**
     * @param Expression|string $column
     * @param iterable $values
     * @param string $boolean
     * @param bool $not
     * @return BaseQueryBuilder
     */
    public function whereBetween(Expression|string $column, iterable $values, string $boolean = 'and', bool $not = false): BaseQueryBuilder
    {
        $this->builder->whereBetween($column, $values, $boolean, $not);
        return $this->baseQueryBuilder;
    }


    /**
     * @param Expression|string $column
     * @param iterable $values
     * @param string $boolean
     * @return BaseQueryBuilder
     */
    public function whereNotBetween(Expression|string $column, iterable $values, string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->whereNotBetween($column, $values, $boolean);
        return $this->baseQueryBuilder;
    }

    /**
     * @param Expression|string $column
     * @param array $values
     * @param string $boolean
     * @param bool $not
     * @return BaseQueryBuilder
     */
    public function whereBetweenColumns(Expression|string $column, array $values, string $boolean = 'and', bool $not = false): BaseQueryBuilder
    {
        $this->builder->whereBetweenColumns($column, $values, $boolean, $not);
        return $this->baseQueryBuilder;
    }


    /**
     * @param Expression|string $column
     * @param array $values
     * @param string $boolean
     * @return BaseQueryBuilder
     */
    public function whereNotBetweenColumns(Expression|string $column, array $values, string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->whereNotBetweenColumns($column, $values, $boolean);
        return $this->baseQueryBuilder;
    }

    /**
     * @param Expression|string $column
     * @param mixed $values
     * @param string $boolean
     * @param bool $not
     * @return BaseQueryBuilder
     */
    public function whereIn(Expression|string $column, mixed $values, string $boolean = 'and', bool $not = false): BaseQueryBuilder
    {
        $this->builder->whereIn($column, $values, $boolean, $not);
        return $this->baseQueryBuilder;
    }

    /**
     * @param Expression|string $column
     * @param mixed $values
     * @param string $boolean
     * @return BaseQueryBuilder
     */
    public function whereNotIn(Expression|string $column, mixed $values, string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->whereNotIn($column, $values, $boolean);
        return $this->baseQueryBuilder;
    }

    /**
     * @param array|Expression|string $columns
     * @param string $boolean
     * @param bool $not
     * @return BaseQueryBuilder
     */
    public function whereNull(array|Expression|string $columns, string $boolean = 'and', bool $not = false): BaseQueryBuilder
    {
        $this->builder->whereNull($columns, $boolean, $not);
        return $this->baseQueryBuilder;
    }


    /**
     * @param array|Expression|string $columns
     * @param string $boolean
     * @return BaseQueryBuilder
     */
    public function whereNotNull(array|Expression|string $columns, string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->whereNotNull($columns, $boolean);
        return $this->baseQueryBuilder;
    }


    /**
     * @param Expression|string $column
     * @param string $operator
     * @param DateTimeInterface|string|null $value
     * @param string $boolean
     * @return BaseQueryBuilder
     */
    public function whereDate(Expression|string $column, string $operator, DateTimeInterface|string|null $value = null, string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->whereDate($column, $operator, $value, $boolean);
        return $this->baseQueryBuilder;
    }


    /**
     * @param Expression|string $column
     * @param string $operator
     * @param DateTimeInterface|int|string|null $value
     * @param string $boolean
     * @return BaseQueryBuilder
     */
    public function whereMonth(Expression|string $column, string $operator, DateTimeInterface|int|string|null $value = null, string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->whereMonth($column, $operator, $value, $boolean);
        return $this->baseQueryBuilder;
    }

    /**
     * @param Expression|string $column
     * @param string $operator
     * @param DateTimeInterface|string|int|null $value
     * @param string $boolean
     * @return BaseQueryBuilder
     */
    public function whereDay(Expression|string $column, string $operator, DateTimeInterface|string|int|null $value = null, string $boolean = 'and'): BaseQueryBuilder
    {
        $this->builder->whereDay($column, $operator, $value, $boolean);
        return $this->baseQueryBuilder;
    }


    /**
     * @param Expression|string $column
     * @param string $operator
     * @param DateTimeInterface|string|int|null $value
     * @param string $boolean
     * @return BaseQueryBuilder
     */
    public function whereYear(Expression|string $column, string $operator, DateTimeInterface|string|int|null $value = null, string $boolean = 'and'): BaseQueryBuilder
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
    public function whereTime(Expression|string $column, string $operator, DateTimeInterface|string|null $value = null, string $boolean = 'and'): BaseQueryBuilder
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
     * @param array|null $columns
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function getAllDataPaginated(array $whereClause = [], array|null $columns = null, ?int $perPage = null): LengthAwarePaginator
    {
        if (!$perPage) {
            $perPage = self::getPerPage();
        }

        if ($columns) {
            $this->builder->addSelect($columns);
        }
        return $this->builder
            ->where($whereClause)
            ->paginate($perPage);
    }

    /**
     * @param array $whereClause
     * @param array|null $columns
     * @return Collection
     */
    public function getAllData(array $whereClause = [], array|null $columns = null): Collection
    {
        if ($columns) {
            $this->builder->addSelect($columns);
        }
        return $this->builder
            ->where($whereClause)
            ->get();
    }

    /**
     * @param string|int|array $id
     * @param array|null $columns
     * @return Builder|Builder[]|Collection|T|null
     */
    public function getDataById(string|int|array $id, array|null $columns = null): Model|Collection|Builder|array|null
    {
        if ($columns) {
            $this->builder->addSelect($columns);
        }
        return $this->builder->find($id);
    }

    /**
     * @param array $whereClause
     * @param array|null $columns
     * @return Builder|T|null
     */
    public function getSingleData(array $whereClause = [], array|null $columns = null): Model|Builder|null
    {
        if ($columns) {
            $this->builder->addSelect($columns);
        }
        return $this->builder
            ->where($whereClause)
            ->first();
    }

    /**
     *
     * @param array $requestedData
     * @return T|Builder
     */
    public function addNewData(array $requestedData): Model|Builder
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
    public function updateDataById(string|int $id, array $requestedData, array $columns = ["*"], bool $isReturnObject = true): Model|Collection|Builder|int|array|null
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
     * @return Collection|int
     */
    public function updateDataByWhereClause(array $whereClause, array $requestedData, array $columns = ["*"], bool $isReturnObject = false): Collection|int
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
    public function deleteDataById(string|int $id): mixed
    {
        return $this->builder
            ->where("id", $id)
            ->delete();
    }

    /**
     * @param array $whereClause
     * @return mixed
     */
    public function deleteDataByWhereClause(array $whereClause): mixed
    {
        return $this->builder
            ->where($whereClause)
            ->delete();
    }
}
