<?php

namespace App\Providers;

use Amethyst\Notifications\BaseNotification;
use App\Events\NotificationEvent;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AmethystServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app('amethyst')->pushMorphRelation('ownable', 'owner', 'user');
    }
}
