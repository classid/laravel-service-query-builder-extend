<?php

namespace Classid\LaravelServiceQueryBuilderExtend\Traits;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait ValidationInput
{
    protected array $requestedData;

    /**
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
        return $validator;
    }

    /**
     * Validates inputs.
     *
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
     * @return $this
     */
    protected function setRequestedData(array $requestedData): self
    {
        $this->requestedData = $requestedData;
        return $this;
    }

    /**
     * @return array
     */
    protected function getValidatedData(): array
    {
        return $this->requestedData;
    }
}
