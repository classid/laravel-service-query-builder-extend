<?php

namespace Classid\LaravelServiceQueryBuilderExtend\Contracts\Abstracts;


use Classid\LaravelServiceQueryBuilderExtend\Contracts\Interfaces\IModel;
use Classid\LaravelServiceQueryBuilderExtend\Traits\ValidationInput;

abstract class BaseService
{
    use ValidationInput;

    /**
     * @param IModel $model
     * @param array $relations
     * @return bool
     */
    protected function isRecordUsed(IModel $model, array $relations):bool{
        foreach ($relations as $relation){
            if($model->{$relation}()->exists()){
                return true;
            };
        }
        return false;
    }
}
