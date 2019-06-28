<?php

namespace App\Providers;

use App\Model\Update\BarNotification;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\ServiceProvider;
use Queue;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Illuminate\Contracts\Auth\Registrar');
        require_once __DIR__.'/../Http/helpers.php';
    }

    public function boot()
    {
        error_reporting(E_ALL ^ E_NOTICE);
        Queue::failing(function (JobFailed $event) {
            loging('Failed Job - '.$event->connectionName, json_encode($event->data));
            $failedid = $event->failedId;
            //\Artisan::call('queue:retry',['id'=>[$failedid]]);
        });
        // Please note the different namespace
        // and please add a \ in front of your classes in the global namespace
        \Event::listen('cron.collectJobs', function () {
            \Cron::add('example1', '* * * * *', function () {
                $this->index();

                return 'No';
            });

            \Cron::add('example2', '*/2 * * * *', function () {
                // Do some crazy things successfully every two minute
            });

            \Cron::add('disabled job', '0 * * * *', function () {
                // Do some crazy things successfully every hour
            }, false);
        });

        $this->composer();
    }

    public function composer()
    {
        \View::composer('themes.default1.update.notification', function () {
            $notification = new BarNotification();
            $not = [
                'notification' => $notification->where('value', '!=', '')->get(),
            ];
            view()->share($not);
        });
    }

}
