<?php

namespace App\Http\Controllers;

use App\Models\Director;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'director_id' => ['nullable', Rule::exists('directors', 'director_id')],
            'new_director_name' => ['nullable', 'string', 'max:100'],
            'new_director_birth_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'new_director_nationality' => ['nullable', 'string', 'max:100'],
        ]);

        $directorId = $validated['director_id'] ?? null;

        if (! $directorId && (
            $request->filled('new_director_name') ||
            $request->filled('new_director_birth_year') ||
            $request->filled('new_director_nationality')
        )) {
            $request->validate([
                'new_director_name' => ['required', 'string', 'max:100'],
                'new_director_birth_year' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
                'new_director_nationality' => ['required', 'string', 'max:100'],
            ]);

            $director = Director::create([
                'name' => trim((string) $validated['new_director_name']),
                'date_of_birth' => Carbon::createFromDate((int) $validated['new_director_birth_year'], 1, 1)->toDateString(),
                'nationality' => trim((string) $validated['new_director_nationality']),
            ]);

            $directorId = $director->director_id;
        }

        $movieData = collect($validated)
            ->only(['title', 'release_year', 'rating', 'language', 'description', 'poster_url'])
            ->merge(['director_id' => $directorId])
            ->all();

        Movie::create($movieData);

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