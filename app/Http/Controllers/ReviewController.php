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
            'movie_id' => ['required', 'exists:movies,movie_id'],
            'rating' => ['required', 'numeric', 'min:0', 'max:10', 'regex:/^\d{1,2}(\.\d)?$/'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $movie = Movie::findOrFail($validated['movie_id']);

        Review::create([
            'movie_id' => $movie->movie_id,
            'user_id' => auth()->id(),
            'rating' => (float) $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()
            ->route('reviews.create')
            ->with('success', 'Your review for ' . $movie->title . ' was saved.');
    }
}