<?php

namespace App\Models;

use App\Models\Concerns\AssignsManualIncrementingId;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use AssignsManualIncrementingId;

    protected $table = 'movies';

    protected $primaryKey = 'movie_id';

    // tiny list of fields we are allowed to save from the form
    protected $fillable = [
        'title',
        'release_year',
        'rating',
        'language',
        'description',
        'poster_url',
        'director_id',
    ];

    protected $casts = [
        'release_year' => 'integer',
        'rating' => 'decimal:1',
        'director_id' => 'integer',
    ];

    // this joins the movie to its director
    public function director()
    {
        return $this->belongsTo(Director::class, 'director_id', 'director_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'movie_id', 'movie_id');
    }
}