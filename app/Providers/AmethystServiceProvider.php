<?php

namespace App\Providers;

use App\Events\NotificationEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Railken\Amethyst\Notifications\BaseNotification;
use Illuminate\Support\Facades\Config;

class AmethystServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        Event::listen([\Railken\Amethyst\Events\ExporterGenerated::class, \Railken\Amethyst\Events\FileGenerator\FileGenerated::class], function ($event) {
            if (!($event->agent instanceof \Railken\Lem\Agents\SystemAgent)) {
                $file = $event->file;

                $event->agent->notify(new BaseNotification($event, 'The file is now ready. Hurry up!', ['url' => $file->getFullUrl()]));
                event(new NotificationEvent($event->agent, config('app.name'), 'The file is now ready. Hurry up!'));
            }
        });

        Event::listen([\Railken\Amethyst\Events\ExporterFailed::class, \Railken\Amethyst\Events\FileGenerator\FileFailed::class], function ($event) {
            if (!($event->agent instanceof \Railken\Lem\Agents\SystemAgent)) {
                $event->agent->notify(new BaseNotification($event, 'An error has occurred', ['error' => [
                    'message' => $event->error->message,
                ]]));

                event(new NotificationEvent($event->agent, config('app.name'), 'An error has occurred'));
            }
        });
    }

    public function boot()
    {
        app('amethyst')->pushMorphRelation('file', 'model', 'company');
        app('amethyst')->pushMorphRelation('file', 'model', 'office');
        app('amethyst')->pushMorphRelation('file', 'model', 'employee');
        app('amethyst')->pushMorphRelation('file', 'model', 'attendance');

        app('amethyst')->pushMorphRelation('post', 'postable', 'user');
        app('amethyst')->pushMorphRelation('post', 'postable', 'company');
        app('amethyst')->pushMorphRelation('post', 'postable', 'office');
        app('amethyst')->pushMorphRelation('post', 'postable', 'employee');
    }
}
