<?php

namespace Classid\LaravelServiceQueryBuilderExtend\Contracts\Abstracts;


use Classid\LaravelServiceQueryBuilderExtend\Traits\ValidationInput;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService
{
    use ValidationInput;

    /**
     * @param Model $model
     * @param array $relations
     * @return bool
     */
    protected function isRecordUsed(Model $model, array $relations):bool{
        foreach ($relations as $relation){
            if($model->{$relation}()->exists()){
                return true;
            };
        }
        return false;
    }
}
