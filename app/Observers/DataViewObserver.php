<?php

namespace App\Observers;

use Amethyst\Models\DataView;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Yaml\Yaml;
use App\Jobs\TriggerEvent;
use App\Events\DataViewFlush;

class DataViewObserver
{
    /**
     * Handle the DataView "saved" event.
     *
     * @param \Amethyst\Models\DataView $dataView
     */
    public function saved(DataView $dataView)
    {
        TriggerEvent::dispatch(new DataViewFlush)->delay(now()->addSeconds(5));
    }
}
