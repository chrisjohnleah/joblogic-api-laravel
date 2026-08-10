<?php

declare(strict_types=1);

namespace ChrisJohnLeah\JoblogicLaravel\Facades;

use ChrisJohnLeah\JoblogicLaravel\JoblogicManager;
use Illuminate\Support\Facades\Facade;

final class Joblogic extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return JoblogicManager::class;
    }
}
