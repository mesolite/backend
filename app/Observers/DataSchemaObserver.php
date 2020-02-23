<?php

namespace App\Observers;

use Amethyst\DataSchema\Helper;
use Amethyst\DataSchema\Manager;
use Amethyst\Models\DataSchema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Yaml\Yaml;

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
    public function updating(DataSchema $dataSchema)
    {
        $oldName = $dataSchema->getOriginal()['name'];

        if ($dataSchema->name !== $oldName) {
            app('amethyst.data-view')->renameByName($oldName, $dataSchema->name);
            app('amethyst')->get('relation-schema')->getRepository()->newQuery()->where('data', $oldName)->update(['data' => $dataSchema->name]);
            app('amethyst')->get('attribute')->getRepository()->newQuery()->where('model', $oldName)->update(['model' => $dataSchema->name]);

            // Change target for all relations.
            foreach (app('amethyst')->get('relation-schema')->getRepository()->findAll() as $relation) {
                $payload = Yaml::parse($relation->payload);

                if ($payload['target'] === $oldName) {
                    $payload['target'] = $dataSchema->name;
                    $relation->payload = Yaml::dump($payload);
                    $relation->save();
                }
            }
        }
    }

    /**
     * Handle the DataSchema "deleted" event.
     *
     * @param \Amethyst\Models\DataSchema $dataSchema
     */
    public function deleting(DataSchema $dataSchema)
    {
        app('amethyst.data-view')->removeByName($dataSchema->name);
        app('amethyst')->get('relation-schema')->getRepository()->newQuery()->where('data', $dataSchema->name)->delete();
        app('amethyst')->get('attribute')->getRepository()->newQuery()->where('model', $dataSchema->name)->delete();
    }
}
