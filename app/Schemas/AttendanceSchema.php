<?php

namespace App\Schemas;

use Railken\Amethyst\Schemas\AttendanceSchema as Schema;
use Railken\Lem\Attributes;
use Railken\Amethyst\Managers\TaxonomyManager;

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
