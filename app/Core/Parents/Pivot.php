<?php

namespace Core\Parents;

use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;

abstract class Pivot extends Model
{
    use AsPivot;

    public $incrementing = false;

    protected $guarded = [];
}
