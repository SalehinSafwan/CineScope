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

        $movies = DB::table('movie_statistics as mov')
            ->when($selectedGenreId, function ($query) use ($selectedGenreId) {
                $query->whereIn('mov.movie_id', DB::table('movie_genres')->select('movie_id')->where('genre_id', $selectedGenreId));
            })
            ->orderByDesc('mov.average_rating')
            ->orderByDesc('mov.release_year')
            ->get();


        $catalogMovies = $movies->map(function ($movie) {
            $genres = DB::table('movie_genres as mg')
                ->join('genres as g', 'g.genre_id', '=', 'mg.genre_id')
                ->where('mg.movie_id', $movie->movie_id)
                ->orderBy('g.genre_name')
                ->pluck('g.genre_name')
                ->implode(', ');

            $cast = DB::table('movie_cast as mc')
                ->join('actors as a', 'a.actor_id', '=', 'mc.actor_id')
                ->where('mc.movie_id', $movie->movie_id)
                ->orderBy('a.name')
                ->get(['a.name', 'mc.role_name'])
                ->map(function ($entry) {
                    return $entry->role_name ? $entry->name . ' as ' . $entry->role_name : $entry->name;
                })
                ->implode(', ');

            $awards = DB::table('movie_awards as ma')
                ->join('awards as aw', 'aw.award_id', '=', 'ma.award_id')
                ->where('ma.movie_id', $movie->movie_id)
                ->orderByDesc('aw.year')
                ->pluck('aw.award_name')
                ->implode(', ');

            return [
                'movie_id' => $movie->movie_id,
                'title' => $movie->title,
                'year' => (string) $movie->release_year,
                'rating' => number_format((float) ($movie->average_rating ?? 0), 1),
                'genre' => $genres ?: 'Film',
                'cast' => $cast ?: 'Cast details coming soon',
                'director' => $movie->director_name ?: 'Unknown director',
                'description' => $movie->description ?: 'No description yet.',
                'poster_url' => $movie->poster_url,
                'review_count' => (int) ($movie->review_count ?? 0),
                'awards' => $awards ?: 'No awards yet',
                'award_count' => (int) ($movie->award_count ?? 0),
            ];
        })->values();

        $selectedMovie = $selectedMovieId
            ? $catalogMovies->firstWhere('movie_id', $selectedMovieId)
            : null;

        $selectedMovieDetails = null;

        if ($selectedMovieId) {
            $selectedMovieDetails = DB::table('movie_statistics as mov')
                ->where('mov.movie_id', $selectedMovieId)
                ->first();
        }

        $selectedCast = collect();
        $selectedReviews = collect();

        if ($selectedMovieId) {
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

        $awardSpotlight = DB::table('awards')
            ->orderByDesc('year')
            ->take(4)
            ->pluck('award_name')
            ->all();

        $productionCompanies = DB::table('production_companies')
            ->orderBy('name')
            ->take(3)
            ->pluck('name')
            ->all();

        $topActors = DB::table('actors')
            ->orderBy('name')
            ->take(4)
            ->pluck('name')
            ->all();
        
        $recentReviews = DB::table('recent_reviews')->get();

        $actorRankings = DB::select('SELECT * FROM TABLE(get_actor_rankings())');
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
        ]);

        
    }
}