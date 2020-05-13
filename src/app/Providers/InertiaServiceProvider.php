<?php declare(strict_types=1);

namespace App\Providers;

use App\Models\Component;
use App\Models\Session;
use App\Models\Specification;
use App\Models\TestCase;
use App\Models\UseCase;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\ViewErrorBag;
use Inertia\Inertia;

class InertiaServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * @return void
     */
    public function boot()
    {
        $this->registerVersion();
        $this->registerVariables();
    }

    /**
     * @return void
     */
    protected function registerVersion()
    {
        Inertia::version(function () {
            return md5_file(public_path('assets/mix-manifest.json'));
        });
    }

    /**
     * @return void
     */
    protected function registerVariables()
    {
        Inertia::share([
            'app' => function () {
                return [
                    'debug' => env('APP_DEBUG'),
                    'dark_mode' => request()->cookie('dark_mode'),
                    'cookies_accepted' => request()->cookie('cookies_accepted'),
                ];
            },
            'auth' => function () {
                return [
                    'guest' => auth()->guest(),
                    'user' => !auth()->guest() ? [
                        'name' => auth()->user()->name,
                        'first_name' => auth()->user()->first_name,
                        'last_name' => auth()->user()->last_name,
                        'company' => auth()->user()->company,
                        'can' => [
                            'users' => [
                                'viewAny' => auth()->user()->can('viewAny', User::class),
                            ],
                            'sessions' => [
                                'viewAny' => auth()->user()->can('viewAny', Session::class),
                            ],
                            'specifications' => [
                                'viewAny' => auth()->user()->can('viewAny', Specification::class),
                                'create' => auth()->user()->can('create', Specification::class),
                            ],
                            'use_cases' => [
                                'viewAny' => auth()->user()->can('viewAny', UseCase::class),
                                'create' => auth()->user()->can('create', UseCase::class),
                            ],
                            'test_cases' => [
                                'viewAny' => auth()->user()->can('viewAny', TestCase::class),
                                'create' => auth()->user()->can('create', TestCase::class),
                            ],
                        ],
                    ] : [],
                ];
            },
            'errors' => function () {
                return collect(session('errors', new ViewErrorBag())->getBag('default')->getMessages())
                    ->mapWithKeys(function ($value, $key) {
                        return [$key => implode(' ', $value)];
                    });
            },
            'messages' => function () {
                return collect()
                    ->put('success', session('success'))
                    ->put('error', session('error'))
                    ->put('warning', session('warning'))
                    ->put('info', session('info'))
                    ->filter();
            },
            'enums' => [
                'user_roles' => User::getRoleNames(),
            ],
        ]);
    }
}
