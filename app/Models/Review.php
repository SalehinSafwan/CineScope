<?php

namespace App\Models;

use App\Models\Concerns\AssignsManualIncrementingId;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use AssignsManualIncrementingId;

    protected $table = 'reviews';

    protected $primaryKey = 'review_id';

    protected $fillable = [
        'movie_id',
        'user_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id', 'movie_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}