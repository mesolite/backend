<?php

namespace App\Models;

use Railken\Amethyst\Models\Attendance as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(config('amethyst.taxonomy.data.taxonomy.model'));
    }
}