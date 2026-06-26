<?php

namespace App\Http\Controllers;

use App\Models\Director;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * show all the movies on one page
     */
    public function index()
    {
        $movies = Movie::with('director')
            ->orderByDesc('movie_id')
            ->get();

        return view('movies.index', compact('movies'));
    }

    /**
     * open the form for a new movie
     */
    public function create()
    {
        $directors = Director::orderBy('name')->get();

        return view('movies.create', compact('directors'));
    }

    /**
     * save the new movie in the database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'release_year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'rating' => ['required', 'numeric', 'min:0', 'max:10'],
            'language' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'poster_url' => ['nullable', 'string', 'max:500'],
            'director_id' => ['nullable', 'exists:directors,director_id'],
        ]);

        Movie::create($validated);

        return redirect()->route('movies.index')->with('success', 'Movie added.');
    }

    /**
     * open the edit form for one movie
     */
    public function edit(Movie $movie)
    {
        $directors = Director::orderBy('name')->get();

        return view('movies.edit', compact('movie', 'directors'));
    }

    /**
     * update the movie with the new form values
     */
    public function update(Request $request, Movie $movie)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'release_year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'rating' => ['required', 'numeric', 'min:0', 'max:10'],
            'language' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'poster_url' => ['nullable', 'string', 'max:500'],
            'director_id' => ['nullable', 'exists:directors,director_id'],
        ]);

        $movie->update($validated);

        return redirect()->route('movies.index')->with('success', 'Movie updated.');
    }

    /**
     * delete the movie from the database
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();

        return redirect()->route('movies.index')->with('success', 'Movie removed.');
    }
}