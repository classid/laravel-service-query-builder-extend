<?php

namespace Classid\LaravelServiceQueryBuilderExtend\Traits;

use Classid\LaravelServiceQueryBuilderExtend\Contracts\Abstracts\BaseQueryBuilder;
use Classid\LaravelServiceQueryBuilderExtend\Contracts\Abstracts\BaseQueryBuilderExtend;
use Exception;
use RuntimeException;

trait QueryExtend
{
    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws Exception
     */
    public static function __callStatic($name, $arguments)
    {
        /** @var BaseQueryBuilder $instance */
        $instance = new static();
        return $instance->overload($name, $arguments);
    }


    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        return $this->overload($name, $arguments);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws Exception
     */
    public function overload(string $name, array $arguments): mixed
    {
        if (!property_exists($this, 'builder')) {
            throw new RuntimeException("Property 'builder' does not exist or is not initialized.");
        }

        if (method_exists(new BaseQueryBuilderExtend($this), $name)) {
            return (new BaseQueryBuilderExtend($this))->$name(...$arguments);
        }

        if (method_exists((new BaseQueryBuilderExtend($this))->builder->getModel(), "scope" . ucwords($name))) {
            return ((new BaseQueryBuilderExtend($this))->forwardScope("scope" . ucwords($name), $arguments));
        }

        if (method_exists($this, "query" . ucwords($name))) {
            return $this->{"query" . ucwords($name)}(...$arguments);
        }


        return $this->builder->$name(...$arguments);
    }
}
