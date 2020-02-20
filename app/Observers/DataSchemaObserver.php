<?php

namespace App\Observers;

use Amethyst\DataSchema\Helper;
use Amethyst\DataSchema\Manager;
use Amethyst\Models\DataSchema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DataSchemaObserver
{
    /**
     * Handle the DataSchema "created" event.
     *
     * @param \Amethyst\Models\DataSchema $dataSchema
     */
    public function created(DataSchema $dataSchema)
    {
        app('amethyst.data-view')->createByName($dataSchema->name);
    }

    /**
     * Handle the DataSchema "updated" event.
     *
     * @param \Amethyst\Models\DataSchema $dataSchema
     */
    public function updated(DataSchema $dataSchema)
    {
        $oldName = $dataSchema->getOriginal()['name'];

        if ($dataSchema->name !== $oldName) {
            app('amethyst.data-view')->renameByName($oldName, $dataSchema->name);
        }

    }

    /**
     * Handle the DataSchema "deleted" event.
     *
     * @param \Amethyst\Models\DataSchema $dataSchema
     */
    public function deleted(DataSchema $dataSchema)
    {
        app('amethyst.data-view')->removeByName($dataSchema->name);
    }
}
