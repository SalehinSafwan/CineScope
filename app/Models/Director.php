<?php

namespace App\Models;

use App\Models\Concerns\AssignsManualIncrementingId;
use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    use AssignsManualIncrementingId;

    protected $table = 'directors';

    protected $primaryKey = 'director_id';

    // just the bits we want to mass assign
    protected $fillable = [
        'name',
        'date_of_birth',
        'nationality',
        'picture_url',
    ];

    // one director can have lots of movies
    public function movies()
    {
        return $this->hasMany(Movie::class, 'director_id', 'director_id');
    }
}