<?php

namespace App\Observers;

use Amethyst\AttributeSchema\Helper;
use Amethyst\AttributeSchema\Manager;
use Amethyst\Models\AttributeSchema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Yaml\Yaml;

class AttributeSchemaObserver
{
    /**
     * Handle the AttributeSchema "created" event.
     *
     * @param \Amethyst\Models\AttributeSchema $attributeSchema
     */
    public function created(AttributeSchema $attributeSchema)
    {
        app('amethyst.data-view')->createAttributeByName($attributeSchema->model, $attributeSchema->name);

        if ($attributeSchema->schema === 'BelongsTo') {
            $this->syncRelationSchema($attributeSchema);
        }
    }

    /**
     * Handle the AttributeSchema "updated" event.
     *
     * @param \Amethyst\Models\AttributeSchema $attributeSchema
     */
    public function updated(AttributeSchema $attributeSchema)
    {
        $oldName = $attributeSchema->getOriginal()['name'];

        if ($attributeSchema->name !== $oldName) {
            app('amethyst.data-view')->renameAttributeByName($attributeSchema->model, $oldName, $attributeSchema->name);

        }

        $fields = ['required', 'options'];

        foreach ($fields as $field) {

            $oldField = $attributeSchema->getOriginal()[$field];

            if ($attributeSchema->$field !== $oldField) {
                app('amethyst.data-view')->regenerateAttributeByName($attributeSchema->model, $attributeSchema->name);
            }
        }

        if ($attributeSchema->schema === 'BelongsTo') {
            $this->syncRelationSchema($attributeSchema);
        }
    }

    /**
     * Handle the AttributeSchema "deleted" event.
     *
     * @param \Amethyst\Models\AttributeSchema $attributeSchema
     */
    public function deleted(AttributeSchema $attributeSchema)
    {
        app('amethyst.data-view')->removeAttributeByName($attributeSchema->model, $attributeSchema->name);
    }

    /**
     * Sync with Relation schema for attributes BelongsTo and MorphTo
     *
     * @param \Amethyst\Models\AttributeSchema $attributeSchema
     */
    public function syncRelationSchema(AttributeSchema $attributeSchema)
    {
        $newOptions = (object) Yaml::parse($attributeSchema->options);
        $originalOptions = (object) Yaml::parse(
            $attributeSchema->getOriginal()['options'] 
            ?? Yaml::dump([
                'relationName' => $newOptions->relationName,
                'relationData' => null // force create
        ]));

        $newRelationName = $newOptions->relationName;
        $oldRelationName = $originalOptions->relationName;

        $this->renameRelationSchemaIfRequired(
            $attributeSchema,
            $originalOptions->relationName,
            $newOptions->relationName
        );

        $this->changeTargetRelationSchemaIfRequired(
            $attributeSchema,
            $newOptions->relationName,
            $originalOptions->relationData,
            $newOptions->relationData
        );

    }

    public function renameRelationSchemaIfRequired(AttributeSchema $attributeSchema, $oldRelationName, $newRelationName)
    {
        if ($oldRelationName === $newRelationName) {
            return;
        }

        $relation = app('amethyst')->get('relation-schema')->getRepository()->findOneBy([
            'data' => $attributeSchema->model,
            'name' => $oldRelationName,
        ]);

        $relation->name = $newRelationName;
        $relation->save();
    }

    public function changeTargetRelationSchemaIfRequired(AttributeSchema $attributeSchema, $relationName, $oldRelationTarget, $newRelationTarget)
    {
        if ($oldRelationTarget === $newRelationTarget) {
            return;
        }

        $relation = app('amethyst')->get('relation-schema')->getRepository()->findOneBy([
            'data' => $attributeSchema->model,
            'name' => $relationName
        ]);

        if ($relation) {
            $relation->payload = Yaml::dump(['target' => $newRelationTarget]);
            $relation->save();
        } else {
            app('amethyst')->get('relation-schema')->createOrFail([
                'data' => $attributeSchema->model,
                'name' => $relationName,
                'type' => $attributeSchema->schema,
                'payload' => Yaml::dump(['target' => $newRelationTarget])
            ]);
        }
    }

}
