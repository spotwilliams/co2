<?php

namespace App\Http\Transformer;

use Co2Control\Entities\Income;
use Co2Control\Entities\MonthlyIncome;

class IncomeTransformer extends Transformer
{
    public function transform(Income $income)
    {
        return [
            'id' => $income->getId(),
            'monthly' => $income instanceof MonthlyIncome
        ];
    }
}
