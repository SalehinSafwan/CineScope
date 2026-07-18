<?php

namespace App\Http\Controllers;

use App\Models\Director;
use App\Models\Movie;
use App\Models\ProductionCompany;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
    /**
     * Show all the movies on one page
     */
    public function index()
    {
        $movies = Movie::with('director')
            ->orderByDesc('movie_id')
            ->get();

        return view('movies.index', compact('movies'));
    }

    /**
     * Open the form for a new movie
     */
    public function create()
    {
        $directors = Director::orderBy('name')->get();
        $productionCompanies = ProductionCompany::orderBy('name')->get();

        return view('movies.create', compact('directors', 'productionCompanies'));
    }

    /**
     * Save the new movie in the database
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
            'production_company_id' => ['nullable', Rule::exists('production_companies', 'production_company_id')],
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

        $movie = Movie::create($movieData);

        // Save production company relation
        if ($request->filled('production_company_id')) {
            DB::table('movie_production')->insert([
                'movie_id' => $movie->movie_id,
                'production_company_id' => $request->production_company_id,
            ]);
        }

        return redirect()->route('movies.index')->with('success', 'Movie added.');
    }

    /**
     * Open the edit form for one movie
     */
    public function edit(Movie $movie)
    {
        $directors = Director::orderBy('name')->get();
        $productionCompanies = ProductionCompany::orderBy('name')->get();

        return view('movies.edit', compact('movie', 'directors', 'productionCompanies'));
    }

    /**
     * Update the movie with the new form values
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
            'production_company_id' => ['nullable', Rule::exists('production_companies', 'production_company_id')],
        ]);

        $movie->update($validated);

        // Update production company relation
        DB::table('movie_production')->where('movie_id', $movie->movie_id)->delete();
        if ($request->filled('production_company_id')) {
            DB::table('movie_production')->insert([
                'movie_id' => $movie->movie_id,
                'production_company_id' => $request->production_company_id,
            ]);
        }

        return redirect()->route('movies.index')->with('success', 'Movie updated.');
    }

    /**
     * Delete the movie from the database
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();

        return redirect()->route('movies.index')->with('success', 'Movie removed.');
    }
}
