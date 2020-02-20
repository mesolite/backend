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
        app('amethyst.data-view')->createAttributeByName($attribute->data, $attribute->name);
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
            app('amethyst.data-view')->renameAttributeByName($attribute->data, $oldName, $attribute->name);
        }

    }

    /**
     * Handle the Attribute "deleted" event.
     *
     * @param \Amethyst\Models\Attribute $attribute
     */
    public function deleted(Attribute $attribute)
    {
        app('amethyst.data-view')->removeAttributeByName($attribute->data, $oldName, $attribute->name);
    }
}
