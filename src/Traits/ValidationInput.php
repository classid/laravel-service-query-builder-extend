<?php

namespace Classid\LaravelServiceQueryBuilderExtend\Traits;

use Classid\LaravelServiceQueryBuilderExtend\Contracts\Abstracts\BaseService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait ValidationInput
{
    protected array $requestedData;
    protected array $validatedData;

    /**
     * @param array $requestedData
     * @param Request|string $request
     * @return array
     * @throws AuthorizationException
     * @throws BindingResolutionException
     * @throws ValidationException
     */
    public function validated(array $requestedData, Request|string $request): array
    {
        $this->setRequestedData($requestedData);

        if ($request instanceof Request) {
            if (!$request->authorize())
                throw new AuthorizationException("You are unauthorized to access this resource");
            $validatedData = Validator::make($requestedData, $request->rules(), $request->messages())->validate();
        } else {
            request()->merge($requestedData);

            /** @var FormRequest $requestClass */
            $requestClass = Container::getInstance()->make($request);

            $validatedData = $requestClass->validated();
        }

        $this->setValidatedData($validatedData);
        return $validatedData;
    }

    /**
     * Validates inputs.
     * @param array $inputs
     * @param array $rules
     * @param array $messages
     * @param array $attributes
     *
     * @return array
     *
     * @throws ValidationException
     */
    public function validate(array $inputs, array $rules, array $messages = [], array $attributes = []): array
    {
        return Validator::make($inputs, $rules, $messages, $attributes)->validate();
    }


    /**
     * @param array $requestedData
     * @return ValidationInput|BaseService
     */
    protected function setRequestedData(array $requestedData): self
    {
        $this->requestedData = $requestedData;
        return $this;
    }


    /**
     * @param string|null $key
     * @return array|string|null
     */
    protected function getRequestedData(string|null $key = null): array|string|null
    {
        if ($key) {
            return $this->requestedData[$key] ?? null;
        }
        return $this->requestedData;
    }


    /**
     * @param string|null $key
     * @return array|string|null
     */
    protected function getValidatedData(string $key = null): array|string|null
    {
        $validated = $this->validatedData;
        
        if ($key) {
            $explodedKeys = explode(".", $key);
            foreach ($explodedKeys as $explodedKey) {
                $validated = is_array($validated) ?
                    ($validated[$explodedKey] ?? null) :
                    null;
            }
        }
        
        return $validated;
    }

    /**
     * @param array $validatedData
     * @return void
     */
    protected function setValidatedData(array $validatedData): void
    {
        $this->validatedData = $validatedData;
    }
}
