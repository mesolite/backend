<?php

namespace App\Observers;

use Amethyst\RelationSchema\Helper;
use Amethyst\RelationSchema\Manager;
use Amethyst\Models\RelationSchema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RelationSchemaObserver
{
    /**
     * Handle the RelationSchema "created" event.
     *
     * @param \Amethyst\Models\RelationSchema $relationSchema
     */
    public function created(RelationSchema $relationSchema)
    {
        app('amethyst.data-view')->createRelationByName($relationSchema->data, $relationSchema->name);
    }

    /**
     * Handle the RelationSchema "updated" event.
     *
     * @param \Amethyst\Models\RelationSchema $relationSchema
     */
    public function updated(RelationSchema $relationSchema)
    {
        $oldName = $relationSchema->getOriginal()['name'];

        if ($relationSchema->name !== $oldName) {
            app('amethyst.data-view')->renameRelationByName($relationSchema->data, $oldName, $relationSchema->name);
        }

    }

    /**
     * Handle the RelationSchema "deleted" event.
     *
     * @param \Amethyst\Models\RelationSchema $relationSchema
     */
    public function deleted(RelationSchema $relationSchema)
    {
        app('amethyst.data-view')->removeRelationByName($relationSchema->data, $oldName, $relationSchema->name);
    }
}
