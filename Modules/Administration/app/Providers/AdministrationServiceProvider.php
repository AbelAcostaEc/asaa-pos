<?php

namespace Modules\Administration\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class AdministrationServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Administration';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'administration';

    // protected array $commands = [];

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Boot the module services.
     * Registers translations so __('administration::users.key') works.
     */
    public function boot(): void
    {
        parent::boot();

        $this->loadTranslationsFrom(
            __DIR__ . '/../../resources/lang',
            'administration'
        );
    }
}
