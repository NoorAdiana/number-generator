<?php 

namespace Inisiatif\NumberGenerator;

use Illuminate\Support\ServiceProvider;

class NumberGeneratorServiceProvider extends ServiceProvider 
{
    
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
    }

}