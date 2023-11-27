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
        }else{
            request()->merge($requestedData);

            /** @var FormRequest $requestClass */
            $requestClass = Container::getInstance()->make($request);
            $requestClass->validateResolved();

            $validatedData = $requestClass->validated();
        }

        $this->setValidatedData($validatedData);
        return $validatedData;
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
     * @return array
     */
    protected function getRequestedData(): array
    {
        return $this->requestedData;
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
    protected function setValidatedData(array $validatedData): void
    {
        $this->validatedData = $validatedData;
    }
}
