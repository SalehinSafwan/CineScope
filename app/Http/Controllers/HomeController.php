<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $selectedGenreId = $request->integer('genre_id') ?: null;
        $selectedMovieId = $request->integer('movie_id') ?: null;

        // 1. Core aggregates
        $stats = [
            ['label' => 'Movies', 'value' => (string) DB::table('movies')->count()],
            ['label' => 'Directors', 'value' => (string) DB::table('directors')->count()],
            ['label' => 'Genres', 'value' => (string) DB::table('genres')->count()],
            ['label' => 'Reviews', 'value' => (string) DB::table('reviews')->count()],
        ];

        $genres = DB::table('genres')
            ->select('genre_id', 'genre_name')
            ->orderBy('genre_name')
            ->get();

        // 2. Fetch Base Movies
        $moviesQuery = DB::table('movie_statistics as mov')
            ->when($selectedGenreId, function ($query) use ($selectedGenreId) {
                $query->whereIn('mov.movie_id', DB::table('movie_genres')->select('movie_id')->where('genre_id', $selectedGenreId));
            })
            ->orderByDesc('mov.average_rating')
            ->orderByDesc('mov.release_year');

        $movies = $moviesQuery->get();
        $movieIds = $movies->pluck('movie_id')->toArray();

        // 3. Solve N+1 problem by Eagerly Eliciting related sets in single bulk queries
        $allGenres = empty($movieIds) ? collect() : DB::table('movie_genres as mg')
            ->join('genres as g', 'g.genre_id', '=', 'mg.genre_id')
            ->whereIn('mg.movie_id', $movieIds)
            ->orderBy('g.genre_name')
            ->get(['mg.movie_id', 'g.genre_name'])
            ->groupBy('movie_id');

        $allCast = empty($movieIds) ? collect() : DB::table('movie_cast as mc')
            ->join('actors as a', 'a.actor_id', '=', 'mc.actor_id')
            ->whereIn('mc.movie_id', $movieIds)
            ->orderBy('a.name')
            ->get(['mc.movie_id', 'a.name', 'mc.role_name'])
            ->groupBy('movie_id');

        $allAwards = empty($movieIds) ? collect() : DB::table('movie_awards as ma')
            ->join('awards as aw', 'aw.award_id', '=', 'ma.award_id')
            ->whereIn('ma.movie_id', $movieIds)
            ->orderByDesc('aw.year')
            ->get(['ma.movie_id', 'aw.award_name'])
            ->groupBy('movie_id');

        // 4. Map and Format collections in memory
        $catalogMovies = $movies->map(function ($movie) use ($allGenres, $allCast, $allAwards) {
            $genresString = isset($allGenres[$movie->movie_id]) 
                ? $allGenres[$movie->movie_id]->pluck('genre_name')->implode(', ') 
                : '';

            $castString = isset($allCast[$movie->movie_id])
                ? $allCast[$movie->movie_id]->map(fn($e) => $e->role_name ? "$e->name as $e->role_name" : $e->name)->implode(', ')
                : '';

            $awardsString = isset($allAwards[$movie->movie_id])
                ? $allAwards[$movie->movie_id]->pluck('award_name')->implode(', ')
                : '';

            return [
                'movie_id' => $movie->movie_id,
                'title' => $movie->title,
                'year' => (string) $movie->release_year,
                'rating' => number_format((float) ($movie->average_rating ?? 0), 1),
                'genre' => $genresString ?: 'Film',
                'cast' => $castString ?: 'Cast details coming soon',
                'director' => $movie->director_name ?: 'Unknown director',
                'description' => $movie->description ?: 'No description yet.',
                'poster_url' => $movie->poster_url,
                'review_count' => (int) ($movie->review_count ?? 0),
                'awards' => $awardsString ?: 'No awards yet',
                'award_count' => (int) ($movie->award_count ?? 0),
            ];
        })->values();

        // 5. Context-dependent item selection
        $selectedMovie = $selectedMovieId ? $catalogMovies->firstWhere('movie_id', $selectedMovieId) : null;
        $selectedMovieDetails = null;
        $selectedCast = collect();
        $selectedReviews = collect();

        if ($selectedMovieId) {
            $selectedMovieDetails = DB::table('movie_statistics')->where('movie_id', $selectedMovieId)->first();
            
            $selectedCast = DB::table('movie_cast as mc')
                ->join('actors as a', 'a.actor_id', '=', 'mc.actor_id')
                ->where('mc.movie_id', $selectedMovieId)
                ->orderBy('a.name')
                ->get(['a.name', 'mc.role_name']);

            $selectedReviews = DB::table('reviews as r')
                ->leftJoin('users as u', 'u.user_id', '=', 'r.user_id')
                ->where('r.movie_id', $selectedMovieId)
                ->orderByDesc('r.review_id')
                ->get(['u.name as reviewer_name', 'r.rating', 'r.comment', 'r.created_at']);
        }

        // 6. Sidebar details & Supplementary views
        $featuredMovies = $catalogMovies->take(3);
        $heroMovie = $featuredMovies->first() ?? [
            'title' => 'No movies seeded yet',
            'year' => date('Y'),
            'rating' => '0.0',
            'genre' => 'Film',
            'cast' => 'Seed the database to populate the homepage',
            'director' => 'Unknown director',
            'description' => 'Add records to the movies table to power the homepage.',
            'poster_url' => null,
            'review_count' => 0,
            'awards' => 'No awards yet',
        ];

        $awardSpotlight = DB::table('awards')->orderByDesc('year')->take(4)->pluck('award_name')->all();
        $productionCompanies = DB::table('production_companies')->orderBy('name')->take(3)->pluck('name')->all();
        $topActors = DB::table('actors')->orderBy('name')->take(4)->pluck('name')->all();
        $recentReviews = DB::table('recent_reviews')->get();
        $actorRankings = DB::select('SELECT * FROM TABLE(get_actor_rankings())');

        // FIX: Grab the anniversaries view data so the welcome layout doesn't error out
        $anniversaries = DB::table('movie_anniversaries')
            ->orderBy('years_since', 'desc')
            ->limit(3)
            ->get();

        return view('welcome', [
            'heroMovie' => $heroMovie,
            'featuredMovies' => $featuredMovies,
            'catalogMovies' => $catalogMovies,
            'genres' => $genres,
            'selectedGenreId' => $selectedGenreId,
            'selectedMovieId' => $selectedMovieId,
            'selectedMovie' => $selectedMovie,
            'selectedMovieDetails' => $selectedMovieDetails,
            'selectedCast' => $selectedCast,
            'selectedReviews' => $selectedReviews,
            'stats' => $stats,
            'awardSpotlight' => $awardSpotlight,
            'productionCompanies' => $productionCompanies,
            'topActors' => $topActors,
            'actorRankings' => $actorRankings,
            'recentReviews' => $recentReviews,
            'anniversaries' => $anniversaries, // Passed successfully!
        ]);
    }

    public function anniversaries()
    {
        $anniversaries = DB::table('movie_anniversaries')
            ->orderBy('years_since', 'desc')
            ->limit(3)
            ->get();

        return view('home', compact('anniversaries'));
    }
}