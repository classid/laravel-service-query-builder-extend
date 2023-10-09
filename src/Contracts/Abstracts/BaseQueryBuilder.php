<?php

namespace App\Contracts\Abstracts;

use App\Concerns\QueryExtend;
use App\Contracts\Interfaces\BaseQueryBuilderInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static getAllDataPaginated(array $whereClause = [], array $columns = ["*"])
 * @method getAllDataPaginated(array $whereClause = [], array $columns = ["*"])
 * @method static getAllData(array $whereClause = [], array $columns = ["*"])
 * @method getAllData(array $whereClause = [], array $columns = ["*"])
 * @method static getDataById(string|int|array $id, array $columns = ["*"])
 * @method getDataById(string|int|array $id, array $columns = ["*"])
 * @method static getSingleData(array $whereClause = [], array $columns = ["*"])
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
        $class = get_called_class();
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
