<?php

namespace App\Providers;

use App\JsonApi\JsonApiRequest;
use App\JsonApi\JsonApiTestResponse;
use App\JsonApi\JsonApiQueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Testing\TestResponse;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class JsonApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Builder::mixin(new JsonApiQueryBuilder());

        TestResponse::mixin(new JsonApiTestResponse());

        Request::mixin(new JsonApiRequest());
    }
}
