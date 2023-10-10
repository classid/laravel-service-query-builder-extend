<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Per Page
    |--------------------------------------------------------------------------
    |
    | This is use to tell our repository on get all paginated method that default
    | per page is 10
    |
    */
    "perpage" => 15,

    /*
    |--------------------------------------------------------------------------
    | Model root namespace
    |--------------------------------------------------------------------------
    |
    | This is used for base namespace for model on repository
    |
    */
    "model_root_namespace" => "App\\Models",

    /*
    |--------------------------------------------------------------------------
    | Target repository dir
    |--------------------------------------------------------------------------
    |
    | This is used for target directory place for generated repository
    |
    */
    "target_repository_dir" => "app/Repositories",

    /*
    |--------------------------------------------------------------------------
    | Target service dir
    |--------------------------------------------------------------------------
    |
    | This is used for target directory place for generated service
    |
    */
    "target_service_dir" => "app/Services",


    /*
    |--------------------------------------------------------------------------
    | Base service extend on generate console
    |--------------------------------------------------------------------------
    |
    | When you want to custom base service and override some method, you can
    | also change console generated parent class of Service
    |
    */
    "base_service_parent_class" => "Classid\LaravelQueryBuilderExtend\Contracts\Abstracts\BaseService",

    /*
    |--------------------------------------------------------------------------
    | Base repository extend on generate console
    |--------------------------------------------------------------------------
    |
    | When you want to custom base repository and override some method, you can
    | also change console generated parent class of Repository
    |
    */
    "base_query_parent_class" => "Classid\LaravelQueryBuilderExtend\Contracts\Abstracts\BaseQueryBuilder",
];