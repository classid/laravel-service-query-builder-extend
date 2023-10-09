<?php

namespace Classid\LaravelQueryBuilderExtend\Contracts\Interfaces;

use Illuminate\Database\Eloquent\Builder;


interface BaseQueryBuilderInterface
{
    public function build(): Builder;

    public static function init(): self;

    public function getBaseQuery(): Builder;
}
