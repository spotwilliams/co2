<?php

namespace App\Http\Handlers\Incomes;

use App\Http\Handlers\Handler;
use App\Http\Requests\CreateIncomeRequest;
use App\Http\Transformer\IncomeTransformer;
use Co2Control\Services\Incomes\SaveIncomeService;

class SaveIncomeHandler extends Handler
{
    const ROUTE = 'incomes/create';
    const NAME = 'incomes.save-action';

    public function __invoke(CreateIncomeRequest $request, SaveIncomeService $saveIncomeService, IncomeTransformer $transformer)
    {
        $income = $saveIncomeService->execute($request);

        return $this->responder->success($transformer->transform($income));
    }
}
