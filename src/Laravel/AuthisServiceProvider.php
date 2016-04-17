<?php namespace Roseffendi\Authis\Laravel;

use Auth;
use Roseffendi\Authis\Authis;
use Illuminate\Support\ServiceProvider;

class AuthisServiceProvider extends ServiceProvider
{
    /**
     * @var boolean
     */
    protected $defer = true;

    /**
     * Register package
     * 
     * @return vod
     */
    public function register()
    {
        $this->app->bind('Roseffendi\Authis\User', 'Roseffendi\Authis\Laravel\User');

        $this->app->singleton('authis', 'Roseffendi\Authis\Authis');
    }

    /**
     * Determine what to provide
     * 
     * @return array
     */
    public function provides()
    {
        return ['authis'];
    }
}