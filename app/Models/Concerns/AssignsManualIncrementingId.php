<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\DB;

trait AssignsManualIncrementingId
{
    protected static function bootAssignsManualIncrementingId(): void
    {
        static::creating(function ($model): void {
            $keyName = $model->getKeyName();

            if (! empty($model->{$keyName})) {
                return;
            }

            $currentMax = DB::table($model->getTable())->max($keyName);

            $model->{$keyName} = ($currentMax ? (int) $currentMax : 0) + 1;
        });
    }
}