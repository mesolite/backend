<?php

namespace App\Observers;

use Amethyst\DataSchema\Helper;
use Amethyst\DataSchema\Manager;
use Amethyst\Models\DataSchema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Yaml\Yaml;
use Amethyst\Core\Attributes\DataNameAttribute;

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

            // Rename all tables field MorphTo
            foreach(app('amethyst')->getData() as $manager) {
                foreach ($manager->getAttributes() as $attribute) {
                    if ($attribute instanceof DataNameAttribute) {
                        $manager->getRepository()->newQuery()->where($attribute->getName(), $oldName)->update([$attribute->getName() => $dataSchema->name]);
                    }
                }
            }

            app('amethyst.data-view')->renameByName($oldName, $dataSchema->name);


            foreach (app('amethyst')->get('attribute-schema')->getRepository()->newQuery()->where('model', $oldName)->get() as $attributeSchema) {
                $attributeSchema->model = $dataSchema->name;
                $attributeSchema->save();
            }

            // Change target for all relations.
            foreach (app('amethyst')->get('relation-schema')->getRepository()->findAll() as $relation) {
                $payload = Yaml::parse($relation->payload);

                if ($relation->data === $oldName) {
                    $relation->data = $dataSchema->name;
                }

                if ($payload['target'] === $oldName) {
                    $payload['target'] = $dataSchema->name;
                    $relation->payload = Yaml::dump($payload);
                }
                
                $relation->save();
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
