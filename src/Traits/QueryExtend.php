<?php

namespace App\Concerns;

use App\Contracts\Abstracts\BaseQueryBuilder;
use App\Contracts\Abstracts\BaseQueryBuilderExtend;

trait QueryExtend
{
    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public static function __callStatic($name, $arguments)
    {
        /** @var BaseQueryBuilder $instance */
        $instance = new static();
        if (!property_exists($instance, 'builder') || is_null($instance->builder)) {
            throw new \Exception("Property 'builder' does not exist or is not initialized.");
        }


        if (method_exists(new BaseQueryBuilderExtend($instance), $name)) {
            return ((new BaseQueryBuilderExtend($instance))->$name(...$arguments));
        }


        if (method_exists((new BaseQueryBuilderExtend($instance))->builder->getModel(), "scope".ucwords($name))) {
            return ((new BaseQueryBuilderExtend($instance))->forwardScope("scope".ucwords($name),$arguments));
        }


        if (method_exists($instance, "query" . ucwords($name))) {
            return $instance->{"query" . ucwords($name)}(...$arguments);
        }

        return $instance->build()->$name(...$arguments);
    }


    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        if (!property_exists($this, 'builder') || is_null($this->builder)) {
            throw new \Exception("Property 'builder' does not exist or is not initialized.");
        }

        if (method_exists(new BaseQueryBuilderExtend($this), $name)) {
            return (new BaseQueryBuilderExtend($this))->$name(...$arguments);
        }

        if (method_exists((new BaseQueryBuilderExtend($this))->builder->getModel(), "scope".ucwords($name))) {
            return ((new BaseQueryBuilderExtend($this))->forwardScope("scope".ucwords($name),$arguments));
        }

        if (method_exists($this, "query" . ucwords($name))) {
            return $this->{"query" . ucwords($name)}(...$arguments);
        }

        return $this->build()->$name(...$arguments);
    }
}
