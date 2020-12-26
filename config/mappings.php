<?php

use App\Infrastructure\Doctrine\Embeddables;
use App\Infrastructure\Doctrine\Mappings;

return [
    // Embeddables
    Embeddables\CurrencyMapping::class,
    Embeddables\EmailMapping::class,
    Embeddables\MoneyMapping::class,
    Embeddables\MonthMapping::class,

    // Entities
    Mappings\EntityMapping::class,
    Mappings\IncomeMapping::class,
    Mappings\EventualIncomeMapping::class,
    Mappings\MonthlyIncomeMapping::class,
    Mappings\PeriodMapping::class,
    Mappings\PersonMapping::class,
    Mappings\UserMapping::class,
];
