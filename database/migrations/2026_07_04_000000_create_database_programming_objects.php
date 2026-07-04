<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'oracle') {
            $this->createOracleObjects();

            return;
        }

        $this->createPortableObjects();
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'oracle') {
            $this->dropOracleObjects();

            return;
        }

        $this->dropPortableObjects();
    }

    private function createPortableObjects(): void
    {
        DB::unprepared('DROP VIEW IF EXISTS movie_overview_view');
        DB::unprepared('CREATE VIEW movie_overview_view AS
            SELECT
                m.movie_id,
                m.title,
                m.release_year,
                m.rating,
                m.language,
                m.description,
                m.poster_url,
                m.director_id,
                d.name AS director_name,
                (SELECT COALESCE(ROUND(AVG(r.rating), 1), 0) FROM reviews r WHERE r.movie_id = m.movie_id) AS average_rating,
                (SELECT COUNT(*) FROM reviews r WHERE r.movie_id = m.movie_id) AS review_count
            FROM movies m
            LEFT JOIN directors d ON d.director_id = m.director_id');

        DB::unprepared('DROP TRIGGER IF EXISTS trg_reviews_ai_rating');
        DB::unprepared('CREATE TRIGGER trg_reviews_ai_rating AFTER INSERT ON reviews BEGIN
            UPDATE movies
               SET rating = (SELECT COALESCE(ROUND(AVG(rating), 1), 0) FROM reviews WHERE movie_id = NEW.movie_id)
             WHERE movie_id = NEW.movie_id;
        END');

        DB::unprepared('DROP TRIGGER IF EXISTS trg_movie_awards_year_check');
        DB::unprepared("CREATE TRIGGER trg_movie_awards_year_check BEFORE INSERT ON movie_awards BEGIN
            SELECT CASE
                WHEN (SELECT year FROM awards WHERE award_id = NEW.award_id) <
                     (SELECT release_year FROM movies WHERE movie_id = NEW.movie_id)
                THEN RAISE(ABORT, 'Award year cannot be earlier than the movie release year')
            END;
        END");

        DB::unprepared('DROP TRIGGER IF EXISTS trg_movies_ad_cleanup');
        DB::unprepared('CREATE TRIGGER trg_movies_ad_cleanup AFTER DELETE ON movies BEGIN
            DELETE FROM reviews WHERE movie_id = OLD.movie_id;
            DELETE FROM movie_awards WHERE movie_id = OLD.movie_id;
            DELETE FROM movie_cast WHERE movie_id = OLD.movie_id;
            DELETE FROM movie_genres WHERE movie_id = OLD.movie_id;
            DELETE FROM movie_production WHERE movie_id = OLD.movie_id;
        END');
    }

    private function dropPortableObjects(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_movies_ad_cleanup');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_movie_awards_year_check');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_reviews_ai_rating');
        DB::unprepared('DROP VIEW IF EXISTS movie_overview_view');
    }

    private function createOracleObjects(): void
    {
        DB::unprepared('CREATE OR REPLACE TYPE number_table_t AS TABLE OF NUMBER');

        DB::unprepared('CREATE OR REPLACE FUNCTION movie_review_average_fn(p_movie_id IN NUMBER)
            RETURN NUMBER
        IS
            l_rating NUMBER;
        BEGIN
            SELECT NVL(ROUND(AVG(rating), 1), 0)
              INTO l_rating
              FROM reviews
             WHERE movie_id = p_movie_id;

            RETURN l_rating;
        END;');

        DB::unprepared('CREATE OR REPLACE PROCEDURE refresh_movie_rating_proc(p_movie_id IN NUMBER)
        IS
        BEGIN
            UPDATE movies
               SET rating = movie_review_average_fn(p_movie_id)
             WHERE movie_id = p_movie_id;
        END;');

        DB::unprepared('CREATE OR REPLACE PROCEDURE attach_movie_genres_proc(
            p_movie_id IN NUMBER,
            p_genres IN number_table_t
        )
        IS
        BEGIN
            FOR i IN 1 .. p_genres.COUNT LOOP
                INSERT INTO movie_genres (movie_id, genre_id)
                VALUES (p_movie_id, p_genres(i));
            END LOOP;
        END;');

        DB::unprepared('CREATE OR REPLACE VIEW movie_overview_view AS
            SELECT
                m.movie_id,
                m.title,
                m.release_year,
                m.rating,
                m.language,
                m.description,
                m.poster_url,
                m.director_id,
                d.name AS director_name,
                movie_review_average_fn(m.movie_id) AS average_rating,
                (SELECT COUNT(*) FROM reviews r WHERE r.movie_id = m.movie_id) AS review_count
            FROM movies m
            LEFT JOIN directors d ON d.director_id = m.director_id');

        DB::unprepared("CREATE OR REPLACE VIEW movie_people_union_vw AS
            SELECT 'DIRECTOR' AS person_type, name AS person_name FROM directors
            UNION ALL
            SELECT 'ACTOR' AS person_type, name AS person_name FROM actors");

        DB::unprepared('CREATE OR REPLACE TRIGGER trg_reviews_ai_rating
            AFTER INSERT ON reviews
            FOR EACH ROW
        BEGIN
            refresh_movie_rating_proc(:NEW.movie_id);
        END;');

        DB::unprepared("CREATE OR REPLACE TRIGGER trg_movie_awards_year_check
            BEFORE INSERT ON movie_awards
            FOR EACH ROW
        DECLARE
            l_award_year awards.year%TYPE;
            l_release_year movies.release_year%TYPE;
        BEGIN
            SELECT year INTO l_award_year FROM awards WHERE award_id = :NEW.award_id;
            SELECT release_year INTO l_release_year FROM movies WHERE movie_id = :NEW.movie_id;

            IF l_award_year < l_release_year THEN
                RAISE_APPLICATION_ERROR(-20001, 'Award year cannot be earlier than the movie release year');
            END IF;
        END;");

        DB::unprepared('CREATE OR REPLACE TRIGGER trg_movies_bd_cleanup
            BEFORE DELETE ON movies
            FOR EACH ROW
        BEGIN
            DELETE FROM reviews WHERE movie_id = :OLD.movie_id;
            DELETE FROM movie_awards WHERE movie_id = :OLD.movie_id;
            DELETE FROM movie_cast WHERE movie_id = :OLD.movie_id;
            DELETE FROM movie_genres WHERE movie_id = :OLD.movie_id;
            DELETE FROM movie_production WHERE movie_id = :OLD.movie_id;
        END;');
    }

    private function dropOracleObjects(): void
    {
        DB::unprepared('DROP TRIGGER trg_movies_bd_cleanup');
        DB::unprepared('DROP TRIGGER trg_movie_awards_year_check');
        DB::unprepared('DROP TRIGGER trg_reviews_ai_rating');
        DB::unprepared('DROP VIEW movie_people_union_vw');
        DB::unprepared('DROP VIEW movie_overview_view');
        DB::unprepared('DROP PROCEDURE attach_movie_genres_proc');
        DB::unprepared('DROP PROCEDURE refresh_movie_rating_proc');
        DB::unprepared('DROP FUNCTION movie_review_average_fn');
        DB::unprepared('DROP TYPE number_table_t');
    }
};