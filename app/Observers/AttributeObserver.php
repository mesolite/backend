<?php

namespace App\Observers;

use Amethyst\Attribute\Helper;
use Amethyst\Attribute\Manager;
use Amethyst\Models\Attribute;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AttributeObserver
{
    /**
     * Handle the Attribute "created" event.
     *
     * @param \Amethyst\Models\Attribute $attribute
     */
    public function created(Attribute $attribute)
    {
        app('amethyst.data-view')->createAttributeByName($attribute->model, $attribute->name);
    }

    /**
     * Handle the Attribute "updated" event.
     *
     * @param \Amethyst\Models\Attribute $attribute
     */
    public function updated(Attribute $attribute)
    {
        $oldName = $attribute->getOriginal()['name'];

        if ($attribute->name !== $oldName) {
            app('amethyst.data-view')->renameAttributeByName($attribute->model, $oldName, $attribute->name);
        }

        $fields = ['required', 'options'];

        foreach ($fields as $field) {

            $oldField = $attribute->getOriginal()[$field];

            if ($attribute->$field !== $oldField) {
                app('amethyst.data-view')->regenerateAttributeByName($attribute->model, $attribute->name);
            }
        }

        $oldModel = $attribute->getOriginal()['model'];

        if ($attribute->model !== $oldModel) {
            app('amethyst.data-view')->moveAttributeByName($attribute->model, $oldModel, $attribute->name);
        }
    }

    /**
     * Handle the Attribute "deleted" event.
     *
     * @param \Amethyst\Models\Attribute $attribute
     */
    public function deleted(Attribute $attribute)
    {
        app('amethyst.data-view')->removeAttributeByName($attribute->model, $attribute->name);
    }
}
