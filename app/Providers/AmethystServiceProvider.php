<?php

namespace App\Providers;

use Amethyst\Notifications\BaseNotification;
use App\Events\NotificationEvent;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AmethystServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        Event::listen([\Amethyst\Events\ExporterGenerated::class, \Amethyst\Events\FileGenerator\FileGenerated::class], function ($event) {
            if (!($event->agent instanceof \Railken\Lem\Agents\SystemAgent)) {
                $file = $event->file;

                $event->agent->notify(new BaseNotification($event, 'The file is now ready. Hurry up!', ['url' => $file->getFullUrl()]));
                event(new NotificationEvent($event->agent, config('app.name'), 'The file is now ready. Hurry up!'));
            }
        });

        Event::listen([\Amethyst\Events\ExporterFailed::class, \Amethyst\Events\FileGenerator\FileFailed::class], function ($event) {
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
        app('amethyst')->pushMorphRelation('ownable', 'owner', 'user');
    }
}
