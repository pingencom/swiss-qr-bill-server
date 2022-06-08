<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Requests\GenerateRequest;
use App\Http\Requests\GenerateRequestApi;
use App\Http\Requests\GenerateRequestInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerRepository();
    }

    private function registerRepository(): void
    {
        $this->app->bind(GenerateRequestInterface::class, GenerateRequest::class);
        $this->app->bind(GenerateRequestInterface::class, GenerateRequestApi::class);
    }
}
