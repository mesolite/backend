<?php

namespace App\Schemas;

use Amethyst\Schemas\AttendanceSchema as Schema;
use Railken\Lem\Attributes;
use Amethyst\Managers\TaxonomyManager;

class AttendanceSchema extends Schema
{
    /**
     * Get all the attributes.
     *
     * @var array
     */
    public function getAttributes()
    {
        $attributes = array_merge(parent::getAttributes(), [
            Attributes\BelongsToAttribute::make('type_id')
                ->setRelationManager(TaxonomyManager::class)
                ->setRelationName('type'),
        ]); 

        return $attributes;
    }
}
