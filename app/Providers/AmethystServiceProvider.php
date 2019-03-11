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


        Config::set('amethyst.category.data.categorizable.attributes.categorizable.options.'.\Railken\Amethyst\Models\Product::class, \Railken\Amethyst\Managers\ProductManager::class);

        Config::set('amethyst.tag.data.tag-entity.taggables.'.\Railken\Amethyst\Models\Activity::class, \Railken\Amethyst\Managers\ActivityManager::class);
        Config::set('amethyst.post.data.post.attributes.postable.options.'.\Railken\Amethyst\Models\Activity::class, \Railken\Amethyst\Managers\ActivityManager::class);
        Config::set('amethyst.post.data.post.attributes.postable.options.'.\Railken\Amethyst\Models\Customer::class, \Railken\Amethyst\Managers\CustomerManager::class);


        Config::set('amethyst.activity.data.activity.attributes.sourceable.options.'.\Railken\Amethyst\Models\Product::class, \Railken\Amethyst\Managers\ProductManager::class);
        Config::set('amethyst.warehouse.data.stock.attributes.stockable.options.'.\Railken\Amethyst\Models\Product::class, \Railken\Amethyst\Managers\ProductManager::class);
        Config::set('amethyst.price.data.price.attributes.priceable.options.'.\Railken\Amethyst\Models\Product::class, \Railken\Amethyst\Managers\ProductManager::class);
        Config::set('amethyst.activity.data.activitiable.attributes.activitiable.options.'.\Railken\Amethyst\Models\Product::class, \Railken\Amethyst\Managers\ProductManager::class);

    }
}
