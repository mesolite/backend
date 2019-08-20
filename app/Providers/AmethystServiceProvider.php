<?php

namespace App\Providers;

use App\Events\NotificationEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Amethyst\Notifications\BaseNotification;
use Illuminate\Support\Facades\Config;

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
        app('amethyst')->pushMorphRelation('price', 'priceable', 'product');
        app('amethyst')->pushMorphRelation('stock', 'stockable', 'product');

        app('amethyst')->pushMorphRelation('ownable', 'owner', 'user');
        app('amethyst')->pushMorphRelation('ownable', 'ownable', 'project');
        app('amethyst')->pushMorphRelation('ownable', 'ownable', 'project');
        app('amethyst')->pushMorphRelation('ownable', 'ownable', 'issue');
        app('amethyst')->pushMorphRelation('ownable', 'ownable', 'issue');
            
        $data = config('amethyst.project.data.project.model');

        $data::morph_to_many(
            'assigned', 
            config('amethyst.user.data.user.model'), 
            'ownable', 
            config('amethyst.owner.data.ownable.table'), 
            'ownable_id',
            'owner_id'
        )->using(config('amethyst.owner.data.ownable.model'))->withPivotValue('relation', 'assigned')->withPivotValue('owner_type', 'user');

        $data = config('amethyst.issue.data.issue.model');
        
        $data::morph_to_many(
            'assigned', 
            config('amethyst.user.data.user.model'), 
            'ownable', 
            config('amethyst.owner.data.ownable.table'), 
            'ownable_id',
            'owner_id'
        )->using(config('amethyst.owner.data.ownable.model'))->withPivotValue('relation', 'assigned')->withPivotValue('owner_type', 'user');
        
        app('amethyst.taxonomy')->addDictionary('project', 'Project Tag', 'tags');
        app('amethyst.taxonomy')->addDictionary('issue', 'Issue Tag', 'tags');
        app('amethyst.taxonomy')->addDictionary('activity', 'Activity Tag', 'tags');

        app('amethyst')->pushMorphRelation('project', 'target', 'customer');
        app('amethyst')->pushMorphRelation('issue', 'issuable', 'project');
        app('amethyst')->pushMorphRelation('activity', 'sourceable', 'issue');

        // app('amethyst')->pushMorphRelation('taxonomable', 'taxonomable', 'project');
        // app('amethyst')->pushMorphRelation('taxonomable', 'taxonomable', 'issue');
        // app('amethyst')->pushMorphRelation('taxonomable', 'taxonomable', 'activity');

        app('amethyst')->pushMorphRelation('post', 'postable', 'user');
        app('amethyst')->pushMorphRelation('post', 'postable', 'customer');
        app('amethyst')->pushMorphRelation('post', 'postable', 'project');
        app('amethyst')->pushMorphRelation('post', 'postable', 'issue');
        app('amethyst')->pushMorphRelation('post', 'postable', 'activity');
        app('amethyst')->pushMorphRelation('post', 'postable', 'company');
        app('amethyst')->pushMorphRelation('post', 'postable', 'office');
        app('amethyst')->pushMorphRelation('post', 'postable', 'employee');

        app('amethyst')->pushMorphRelation('contact', 'contactable', 'customer');
        app('amethyst')->pushMorphRelation('contact', 'contactable', 'user');

        app('amethyst')->pushMorphRelation('file', 'model', 'user');
        app('amethyst')->pushMorphRelation('file', 'model', 'customer');
        app('amethyst')->pushMorphRelation('file', 'model', 'project');
        app('amethyst')->pushMorphRelation('file', 'model', 'issue');
        app('amethyst')->pushMorphRelation('file', 'model', 'activity');

        app('amethyst')->pushMorphRelation('file', 'model', 'company');
        app('amethyst')->pushMorphRelation('file', 'model', 'office');
        app('amethyst')->pushMorphRelation('file', 'model', 'employee');
        app('amethyst')->pushMorphRelation('file', 'model', 'attendance');

        app('amethyst')->pushMorphRelation('model-has-role', 'model', 'user');

    }
}
