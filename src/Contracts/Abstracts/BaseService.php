<?php

namespace Classid\LaravelQueryBuilderExtend\Contracts\Abstracts;


use Classid\LaravelQueryBuilderExtend\Traits\ValidationInput;

abstract class BaseService
{
    use ValidationInput;

    /**
     * @param $model
     * @param array $relations
     * @return bool
     */
    protected function isRecordUsed($model, array $relations):bool{
        foreach ($relations as $relation){
            if($model->{$relation}()->exists()){
                return true;
            };
        }
        return false;
    }
}
