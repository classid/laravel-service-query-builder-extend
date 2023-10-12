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
     * remove on next upgrade
     * @deprecated use validate instead
     * @param array $data
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function validated(array $data, Request $request): array
    {
        if (!$request->authorize())
            throw new AuthorizationException("You are unauthorized to access this resource");

        $validator = Validator::make($data, $request->rules(), $request->messages())->validate();

        $this->setRequestedData($validator);
        $this->setValidatedData($validator);
        return $validator;
    }


    /**
     * @deprecated use setValidatedData instead
     * @param array $requestedData
     * @return ValidationInput|BaseService
     */
    protected function setRequestedData(array $requestedData): self
    {
        $this->requestedData = $requestedData;
        return $this;
    }


    /**
     * @throws BindingResolutionException
     */
    protected function validate(array $requestedData, string $requestClass):FormRequest
    {
        $storeUserRequest = Container::getInstance()->make($requestClass);


        $storeUserRequest->replace($requestedData);
        $storeUserRequest->validateResolved();

        $this->setValidatedData($storeUserRequest->validated());
        return $storeUserRequest;
    }

    /**
     * @return array
     */
    protected function getValidatedData(): array
    {
        return $this->validatedData;
    }

    /**
     * @param array $validatedData
     * @return void
     */
    protected function setValidatedData(array $validatedData):void
    {
        $this->validatedData = $validatedData;
    }
}
