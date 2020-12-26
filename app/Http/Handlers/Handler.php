<?php

namespace App\Http\Handlers;

use App\Http\Responders\Responder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Handler extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /** @var Responder */
    protected $responder;

    public function __construct(Responder $responder)
    {
        $this->responder = $responder;
    }
}
