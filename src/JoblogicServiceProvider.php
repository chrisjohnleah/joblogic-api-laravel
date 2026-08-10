<?php

declare(strict_types=1);

namespace ChrisJohnLeah\JoblogicLaravel;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\ServiceProvider;

final class JoblogicServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/joblogic.php', 'joblogic');

        $this->app->singleton(CacheTokenStore::class, fn ($app): CacheTokenStore => new CacheTokenStore(
            $app->make(Repository::class),
            (string) config('joblogic.cache_prefix', 'joblogic'),
        ));

        $this->app->singleton(JoblogicManager::class, fn ($app): JoblogicManager => new JoblogicManager(
            $app->make(CacheTokenStore::class),
        ));

        $this->app->alias(JoblogicManager::class, 'joblogic');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/joblogic.php' => config_path('joblogic.php'),
        ], 'joblogic-config');
    }
}
