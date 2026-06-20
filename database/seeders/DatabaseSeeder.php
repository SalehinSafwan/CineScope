<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('directors')->updateOrInsert(
            ['director_id' => 1],
            [
                'name' => 'Ava Laurent',
                'date_of_birth' => '1978-04-12',
                'nationality' => 'French',
                'picture_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('directors')->updateOrInsert(
            ['director_id' => 2],
            [
                'name' => 'Marcus Reed',
                'date_of_birth' => '1981-09-28',
                'nationality' => 'American',
                'picture_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('genres')->updateOrInsert(['genre_id' => 1], ['genre_name' => 'Thriller', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('genres')->updateOrInsert(['genre_id' => 2], ['genre_name' => 'Drama', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('genres')->updateOrInsert(['genre_id' => 3], ['genre_name' => 'Sci-Fi', 'created_at' => now(), 'updated_at' => now()]);

        DB::table('actors')->updateOrInsert(
            ['actor_id' => 1],
            ['name' => 'Ava Stone', 'date_of_birth' => '1990-01-15', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('actors')->updateOrInsert(
            ['actor_id' => 2],
            ['name' => 'Malik Reed', 'date_of_birth' => '1987-03-22', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('actors')->updateOrInsert(
            ['actor_id' => 3],
            ['name' => 'Nora Bell', 'date_of_birth' => '1994-07-08', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('actors')->updateOrInsert(
            ['actor_id' => 4],
            ['name' => 'Elara Quinn', 'date_of_birth' => '1992-11-03', 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('production_companies')->updateOrInsert(
            ['company_id' => 1],
            ['name' => 'Sunline Pictures', 'country' => 'USA', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('production_companies')->updateOrInsert(
            ['company_id' => 2],
            ['name' => 'North Star Studios', 'country' => 'Canada', 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('awards')->updateOrInsert(
            ['award_id' => 1],
            ['award_name' => 'Best Picture', 'year' => 2026, 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('awards')->updateOrInsert(
            ['award_id' => 2],
            ['award_name' => 'Best Director', 'year' => 2026, 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('awards')->updateOrInsert(
            ['award_id' => 3],
            ['award_name' => 'Audience Choice', 'year' => 2025, 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('awards')->updateOrInsert(
            ['award_id' => 4],
            ['award_name' => 'Top Rated Cast', 'year' => 2024, 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('users')->updateOrInsert(
            ['user_id' => 1],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password_hash' => bcrypt('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('movies')->updateOrInsert(
            ['movie_id' => 1],
            [
                'title' => 'Midnight Signal',
                'release_year' => 2026,
                'rating' => 8.9,
                'language' => 'English',
                'description' => 'A tense neo-noir chase through a city that never sleeps.',
                'poster_url' => null,
                'director_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('movies')->updateOrInsert(
            ['movie_id' => 2],
            [
                'title' => 'Orbit City',
                'release_year' => 2025,
                'rating' => 8.4,
                'language' => 'English',
                'description' => 'A neon-soaked future story about memory, gravity, and second chances.',
                'poster_url' => null,
                'director_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('movies')->updateOrInsert(
            ['movie_id' => 3],
            [
                'title' => 'Last Frame',
                'release_year' => 2024,
                'rating' => 8.1,
                'language' => 'English',
                'description' => 'An intimate drama about a filmmaker chasing one final scene.',
                'poster_url' => null,
                'director_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('movie_genres')->updateOrInsert(['movie_id' => 1, 'genre_id' => 1], []);
        DB::table('movie_genres')->updateOrInsert(['movie_id' => 1, 'genre_id' => 2], []);
        DB::table('movie_genres')->updateOrInsert(['movie_id' => 2, 'genre_id' => 3], []);
        DB::table('movie_genres')->updateOrInsert(['movie_id' => 2, 'genre_id' => 1], []);
        DB::table('movie_genres')->updateOrInsert(['movie_id' => 3, 'genre_id' => 2], []);

        DB::table('movie_cast')->updateOrInsert(['movie_id' => 1, 'actor_id' => 1], ['role_name' => 'Lead Investigator']);
        DB::table('movie_cast')->updateOrInsert(['movie_id' => 1, 'actor_id' => 2], ['role_name' => 'Night Courier']);
        DB::table('movie_cast')->updateOrInsert(['movie_id' => 1, 'actor_id' => 3], ['role_name' => 'Broadcast Analyst']);
        DB::table('movie_cast')->updateOrInsert(['movie_id' => 2, 'actor_id' => 4], ['role_name' => 'Navigator']);
        DB::table('movie_cast')->updateOrInsert(['movie_id' => 3, 'actor_id' => 3], ['role_name' => 'Director']);

        DB::table('movie_production')->updateOrInsert(['movie_id' => 1, 'company_id' => 1], []);
        DB::table('movie_production')->updateOrInsert(['movie_id' => 2, 'company_id' => 2], []);
        DB::table('movie_production')->updateOrInsert(['movie_id' => 3, 'company_id' => 1], []);

        DB::table('movie_awards')->updateOrInsert(['movie_id' => 1, 'award_id' => 1], []);
        DB::table('movie_awards')->updateOrInsert(['movie_id' => 1, 'award_id' => 2], []);
        DB::table('movie_awards')->updateOrInsert(['movie_id' => 2, 'award_id' => 3], []);

        DB::table('reviews')->updateOrInsert(
            ['review_id' => 1],
            [
                'movie_id' => 1,
                'user_id' => 1,
                'rating' => 5,
                'comment' => 'Tight direction and strong performances.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('reviews')->updateOrInsert(
            ['review_id' => 2],
            [
                'movie_id' => 2,
                'user_id' => 1,
                'rating' => 4,
                'comment' => 'Big visuals with a clean emotional core.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
