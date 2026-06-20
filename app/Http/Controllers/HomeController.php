<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            ['label' => 'Movies', 'value' => (string) DB::table('movies')->count()],
            ['label' => 'Directors', 'value' => (string) DB::table('directors')->count()],
            ['label' => 'Genres', 'value' => (string) DB::table('genres')->count()],
            ['label' => 'Reviews', 'value' => (string) DB::table('reviews')->count()],
        ];

        $movies = DB::table('movies as m')
            ->leftJoin('directors as d', 'd.director_id', '=', 'm.director_id')
            ->select('m.movie_id', 'm.title', 'm.release_year', 'm.rating', 'm.language', 'm.description', 'm.poster_url', 'd.name as director_name')
            ->orderByDesc('m.rating')
            ->orderByDesc('m.release_year')
            ->get();

        $featuredMovies = $movies->take(3)->map(function ($movie) {
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

            $reviewSummary = DB::table('reviews')
                ->where('movie_id', $movie->movie_id)
                ->selectRaw('ROUND(NVL(AVG(rating), 0), 1) as average_rating, COUNT(*) as review_count')
                ->first();

            $awards = DB::table('movie_awards as ma')
                ->join('awards as aw', 'aw.award_id', '=', 'ma.award_id')
                ->where('ma.movie_id', $movie->movie_id)
                ->orderByDesc('aw.year')
                ->pluck('aw.award_name')
                ->implode(', ');

            return [
                'title' => $movie->title,
                'year' => (string) $movie->release_year,
                'rating' => number_format((float) ($reviewSummary->average_rating ?? $movie->rating), 1),
                'genre' => $genres ?: 'Film',
                'cast' => $cast ?: 'Cast details coming soon',
                'director' => $movie->director_name ?: 'Unknown director',
                'description' => $movie->description ?: 'No description yet.',
                'poster_url' => $movie->poster_url,
                'review_count' => (int) ($reviewSummary->review_count ?? 0),
                'awards' => $awards ?: 'No awards yet',
            ];
        })->values();

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

        return view('welcome', [
            'heroMovie' => $heroMovie,
            'featuredMovies' => $featuredMovies,
            'stats' => $stats,
            'awardSpotlight' => $awardSpotlight,
            'productionCompanies' => $productionCompanies,
            'topActors' => $topActors,
        ]);
    }
}