<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create()
    {
        $movies = Movie::orderBy('title')->get(['movie_id', 'title', 'release_year']);
        $recentReviews = Review::with('movie')
            ->where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        return view('reviews.create', compact('movies', 'recentReviews'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'movie_id' => ['required', 'exists:MOVIES,MOVIE_ID'],
        'rating' => ['required', 'numeric', 'min:0', 'max:10', 'regex:/^\d{1,2}(\.\d)?$/'],
        'comment' => ['nullable', 'string', 'max:2000'],
    ]);

    $movie = Movie::findOrFail($validated['movie_id']);

    $review = new Review();
    // Pad the string with spaces to match a CHAR column definition if necessary
    // Adjust the length (e.g., 10) to match your exact DB column size
    $review->movie_id = str_pad($movie->movie_id, 10, " ", STR_PAD_RIGHT); 
    $review->user_id  = auth()->id();
    $review->rating   = (float) $validated['rating'];
    $review->comment  = $validated['comment'] ?? null;

    $review->save();

    return redirect()
        ->route('reviews.create')
        ->with('success', 'Your review for ' . $movie->title . ' was saved.');
}
}