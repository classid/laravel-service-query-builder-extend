<?php

namespace Classid\LaravelServiceQueryBuilderExtend\Contracts\Abstracts;

use Classid\LaravelServiceQueryBuilderExtend\Traits\QueryExtend;
use Classid\LaravelServiceQueryBuilderExtend\Contracts\Interfaces\BaseQueryBuilderInterface;
use Closure;
use DateTimeInterface;
use http\Encoding\Stream;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @method static getAllDataPaginated(array $whereClause = [], array $columns = ["*"])
 * @method getAllDataPaginated(array $whereClause = [], array $columns = ["*"])
 * @method static getAllData(array $whereClause = [], array $columns = ["*"])
 * @method getAllData(array $whereClause = [], array $columns = ["*"])
 * @method static getDataById(string|int|array $id, array $columns = ["*"])
 * @method getDataById(string|int|array $id, array $columns = ["*"])
 * @method static  getSingleData(array $whereClause = [], array $columns = ["*"])
 * @method getSingleData(array $whereClause = [], array $columns = ["*"])
 * @method static addNewData(array $requestedData)
 * @method addNewData(array $requestedData)
 * @method static addMultipleData(array $requestedData)
 * @method addMultipleData(array $requestedData)
 * @method static updateDataById(string|int $id, array $requestedData, array $columns = ["*"], bool $isReturnObject = true)
 * @method updateDataById(string|int $id, array $requestedData, array $columns = ["*"], bool $isReturnObject = true)
 * @method static updateDataByWhereClause(array $whereClause, array $requestedData, array $columns = ["*"], bool $isReturnObject = false)
 * @method updateDataByWhereClause(array $whereClause, array $requestedData, array $columns = ["*"], bool $isReturnObject = false)
 * @method static deleteDataById(string|int $id)
 * @method deleteDataById(string|int $id)
 * @method static deleteDataByWhereClause(array $whereClause)
 * @method deleteDataByWhereClause(array $whereClause)
 * @method void function applyFilterParams()
 * @method static BaseQueryBuilder orderColumn(array|string|null $orderableColumns = null, string $direction = "ASC")
 * @method BaseQueryBuilder orderColumn(array|string|null $orderableColumns = null, string $direction = "ASC")
 * @method static BaseQueryBuilder filterColumn(?array $filterableColumns = null, ?array $relationFilterableColumns = null)
 * @method BaseQueryBuilder filterColumn(?array $filterableColumns = null, ?array $relationFilterableColumns = null)
 * @method static BaseQueryBuilder orWhereColumn(array|string $first, ?string $operator = null, ?string $second = null)
 * @method BaseQueryBuilder orWhereColumn(array|string $first, ?string $operator = null, ?string $second = null)
 * @method static BaseQueryBuilder whereColumn(array|string $first, ?string $operator = null, ?string $second = null, ?string $boolean = 'and')
 * @method BaseQueryBuilder whereColumn(array|string $first, ?string $operator = null, ?string $second = null, ?string $boolean = 'and')
 * @method static BaseQueryBuilder whereTime(Expression|string $column, string $operator, DateTimeInterface|string|null $value = null, string $boolean = 'and')
 * @method BaseQueryBuilder whereTime(Expression|string $column, string $operator, DateTimeInterface|string|null $value = null, string $boolean = 'and')
 * @method static BaseQueryBuilder whereYear(Expression|string $column, string $operator, DateTimeInterface|string|int|null $value = null, string $boolean = 'and')
 * @method BaseQueryBuilder whereYear(Expression|string $column, string $operator, DateTimeInterface|string|int|null $value = null, string $boolean = 'and')
 * @method static BaseQueryBuilder whereDay(Expression|string $column, string $operator, DateTimeInterface|string|int|null $value = null, string $boolean = 'and')
 * @method BaseQueryBuilder whereDay(Expression|string $column, string $operator, DateTimeInterface|string|int|null $value = null, string $boolean = 'and')
 * @method static BaseQueryBuilder whereMonth(Expression|string $column, string $operator, DateTimeInterface|string|int|null $value = null, string $boolean = 'and')
 * @method BaseQueryBuilder whereMonth(Expression|string $column, string $operator, DateTimeInterface|string|int|null $value = null, string $boolean = 'and')
 * @method static BaseQueryBuilder whereDate(Expression|string $column, string $operator, DateTimeInterface|string|null $value = null, string $boolean = 'and')
 * @method BaseQueryBuilder whereDate(Expression|string $column, string $operator, DateTimeInterface|string|null $value = null, string $boolean = 'and')
 * @method static BaseQueryBuilder whereNotNull(Expression|string|array $columns, string $boolean = 'and')
 * @method BaseQueryBuilder whereNotNull(Expression|string|array $columns, string $boolean = 'and')
 * @method static BaseQueryBuilder whereNull(array|Expression|string $columns, string $boolean = 'and', bool $not = false)
 * @method BaseQueryBuilder whereNull(array|Expression|string $columns, string $boolean = 'and', bool $not = false)
 * @method static BaseQueryBuilder whereNotIn(Expression|string $column, mixed $values, string $boolean = 'and')
 * @method BaseQueryBuilder whereNotIn(Expression|string $column, mixed $values, string $boolean = 'and')
 * @method static BaseQueryBuilder whereIn(Expression|string $column, mixed $values, string $boolean = 'and', bool $not = false)
 * @method BaseQueryBuilder whereIn(Expression|string $column, mixed $values, string $boolean = 'and', bool $not = false)
 * @method static BaseQueryBuilder whereNotBetweenColumns(Expression|string $column, array $values, string $boolean = 'and')
 * @method BaseQueryBuilder whereNotBetweenColumns(Expression|string $column, array $values, string $boolean = 'and')
 * @method static BaseQueryBuilder whereBetweenColumns(Expression|string $column, array $values, string $boolean = 'and', bool $not = false)
 * @method BaseQueryBuilder whereBetweenColumns(Expression|string $column, array $values, string $boolean = 'and', bool $not = false)
 * @method static BaseQueryBuilder whereNotBetween(Expression|string $column, iterable $values, string $boolean = 'and')
 * @method BaseQueryBuilder whereNotBetween(Expression|string $column, iterable $values, string $boolean = 'and')
 * @method static BaseQueryBuilder whereBetween(Expression|string $column, iterable $values, string $boolean = 'and', bool $not = false)
 * @method BaseQueryBuilder whereBetween(Expression|string $column, iterable $values, string $boolean = 'and', bool $not = false)
 * @method static BaseQueryBuilder whereNot(array|Closure|Expression|string $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method BaseQueryBuilder whereNot(array|Closure|Expression|string $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static BaseQueryBuilder orWhere(array|Closure|Expression|string $column, mixed $operator = null, mixed $value = null)
 * @method BaseQueryBuilder orWhere(array|Closure|Expression|string $column, mixed $operator = null, mixed $value = null)
 * @method static BaseQueryBuilder where(array|string|Closure|Expression $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method BaseQueryBuilder where(array|string|Closure|Expression $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static BaseQueryBuilder orWhereHas(string $relation, Closure|null $callback = null, string $operator = '>=', int $count = 1)
 * @method BaseQueryBuilder orWhereHas(string $relation, Closure|null $callback = null, string $operator = '>=', int $count = 1)
 * @method static BaseQueryBuilder whereHas(string $relation, Closure|null $callback = null, string $operator = '>=', int $count = 1)
 * @method BaseQueryBuilder whereHas(string $relation, Closure|null $callback = null, string $operator = '>=', int $count = 1)
 * @method static BaseQueryBuilder has(Relation|string $relation, string $operator = '>=', int $count = 1, string $boolean = 'and', Closure|null $callback = null)
 * @method BaseQueryBuilder has(Relation|string $relation, string $operator = '>=', int $count = 1, string $boolean = 'and', Closure|null $callback = null)
 * @method static BaseQueryBuilder withSum(array|string $relation, string $column)
 * @method BaseQueryBuilder withSum(array|string $relation, string $column)
 * @method static BaseQueryBuilder withMax(array|string $relation, string $column)
 * @method BaseQueryBuilder withMax(array|string $relation, string $column)
 * @method static BaseQueryBuilder withMin(array|string $relation, string $column)
 * @method BaseQueryBuilder withMin(array|string $relation, string $column)
 * @method static BaseQueryBuilder withCount(mixed $relations)
 * @method BaseQueryBuilder withCount(mixed $relations)
 * @method static BaseQueryBuilder withAvg(array|string $relation, string $column)
 * @method BaseQueryBuilder withAvg(array|string $relation, string $column)
 * @method static BaseQueryBuilder without(array|string $relations)
 * @method BaseQueryBuilder without(array|string $relations)
 * @method static BaseQueryBuilder with(array|string $relations)
 * @method BaseQueryBuilder with(array|string $relations, Closure|string|null $callback = null)
 * @method static int delete()
 * @method int delete()
 * @method static int update(array $requestedData)
 * @method int update(array $requestedData)
 * @method static Collection get()
 * @method Collection get()
 * @mixin QueryExtend
 *
 */
abstract class BaseQueryBuilder implements BaseQueryBuilderInterface
{
    use QueryExtend;

    /**
     * @var Builder $builder
     */
    public Builder $builder;

    /**
     * @param Builder|null $builder
     */
    public function __construct(Builder $builder = null)
    {
        $this->builder = $builder ?? $this->getBaseQuery();
    }

    /**
     * @return Builder
     */
    abstract public function getBaseQuery(): Builder;

    /**
     * This is use for custom builder
     * @return Builder
     */
    public function build(): Builder
    {
        return $this->builder;
    }


    /**
     * This is use for predefined builder
     * @return self
     */
    public static function init(): self
    {
        $class = static::class;
        return new $class;
    }

    /**
     * use to implement custom filter param, call it on (for example RoleQuery) and override
     * @return void
     */
    public function applyFilterParams(): void
    {
    }
}
