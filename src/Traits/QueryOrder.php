<?php

namespace Classid\LaravelServiceQueryBuilderExtend\Traits;

use Classid\LaravelServiceQueryBuilderExtend\Contracts\Abstracts\BaseQueryBuilder;
use Illuminate\Database\Eloquent\Builder;

trait QueryOrder
{
    /**
     * @param array|string|null $orderableColumns
     * @param string $direction
     * @return BaseQueryBuilder|QueryOrder
     */
    public function _orderColumn(array|string|null $orderableColumns = null, string $direction = "ASC"): self
    {
        $orderableColumns = $orderableColumns ?? $this->builder->getModel()->orderableColumns ?? [];
        $this->defaultOrderColumn();

        if (is_array($orderableColumns)) {
            $requestQueryParam = config("queryextend.order_query_param_root") ?
                request()->query(config("queryextend.order_query_param_root"), []) :
                request()->query();


            $columns = array_intersect_key($requestQueryParam, $orderableColumns);

            foreach ($columns as $columnName => $requestDirection) {
                $this->checkDirection($requestDirection);
                $this->builder->orderBy($orderableColumns[$columnName], $requestDirection);
            }
        }

        if (is_string($orderableColumns)) {
            $this->checkDirection($direction);
            $this->builder->orderBy($orderableColumns, $direction);
        }

        return $this;
    }


    /**
     * @return void
     */
    private function defaultOrderColumn(): void
    {
        $this->builder->when(!empty(request()->query("order_created_at")), function (Builder $query) {
            $query->orderBy("created_at", request()->query("order_created_at"));
        });
    }


    /**
     * Use set direction into ASC when direction is not between ASC or DESC
     * @param $requestDirection
     * @return void
     */
    private function checkDirection(&$requestDirection): void
    {
        $requestDirection = strtoupper($requestDirection);

        if ($requestDirection !== "ASC" && $requestDirection !== "DESC") {
            $requestDirection = "ASC";
        }
    }


}
