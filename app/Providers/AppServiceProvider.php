<?php

namespace App\Providers;

use App\Models\Cases;
use App\Models\EducationContent;
use App\Models\Screening;
use App\Models\User;
use App\Policies\CasePolicy;
use App\Policies\EducationPolicy;
use App\Policies\ScreeningPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Register Policies
        Gate::policy(Cases::class,            CasePolicy::class);
        Gate::policy(Screening::class,        ScreeningPolicy::class);
        Gate::policy(EducationContent::class, EducationPolicy::class);
        Gate::policy(User::class,             UserPolicy::class);
    }
}
