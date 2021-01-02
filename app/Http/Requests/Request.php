<?php

namespace App\Http\Requests;

use Illuminate\Http\Request as BaseRequest;

abstract class Request
{
    /** @var BaseRequest */
    protected $request;

    public function __construct(BaseRequest $request)
    {
        $this->request = $request;
    }

    public function validate()
    {
        $this->request->validate($this->rules());
    }

    abstract protected function rules(): array;
}
