<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>CineScope</title>
        <meta name="description" content="CineDB is a cinematic homepage for exploring movies, directors, genres, cast, awards, production companies, and reviews.">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|playfair-display:700,800" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('css/home.css') }}">
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            .home-flash {
                margin: 16px 0 0;
                padding: 12px 16px;
                border-radius: 12px;
                background: rgba(245, 158, 11, 0.12);
                border: 1px solid rgba(245, 158, 11, 0.22);
                color: #fff1c2;
            }

            .tiny-note {
                margin: 18px 0 0;
                color: rgba(255, 255, 255, 0.82);
                font-size: 0.95rem;
            }

            .catalog-filter {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 14px;
                margin: 18px 0 24px;
            }

            .catalog-filter .filter-actions {
                grid-column: 1 / -1;
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .catalog-grid,
            .catalog-detail-grid {
                display: grid;
                gap: 16px;
                margin-top: 18px;
            }

            .catalog-grid {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            }

            .catalog-card {
                padding: 16px;
                border-radius: 18px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                background: linear-gradient(160deg, rgba(15, 23, 42, 0.96), rgba(31, 41, 55, 0.96));
            }

            .catalog-card--active {
                outline: 2px solid rgba(245, 158, 11, 0.35);
            }

            .catalog-card h4 {
                margin: 8px 0 10px;
            }

            .catalog-card p {
                margin: 0 0 6px;
                color: rgba(229, 231, 235, 0.85);
            }

            .detail-list {
                display: grid;
                gap: 10px;
                margin-top: 12px;
            }

            .detail-item {
                padding: 12px;
                border-radius: 14px;
                background: rgba(11, 18, 32, 0.9);
                border: 1px solid rgba(255, 255, 255, 0.08);
            }

            @media (max-width: 760px) {
                .catalog-filter {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body class="cine-home">
        <div class="page-shell">
            <header class="topbar">
                <div class="brand-lockup">
                    <div class="brand-mark">C</div>
                    <div>
                        <p class="eyebrow">Movie Catalog</p>
                        <h1>CineScope</h1>
                    </div>
                </div>

                <nav class="topnav" aria-label="Primary">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="#featured">Featured</a>
                    <a href="#catalog">Catalog</a>
                    <a href="#awards">Awards</a>
                    <a href="#reviews">Reviews</a>
                    @auth
                        @if (auth()->user()->role === 'user')
                            <a href="{{ route('reviews.create') }}">Write review</a>
                        @endif
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('movies.index') }}">Admin Section</a>
                        @endif
                    @endauth
                </nav>

                <div class="hero-actions">
                    @guest
                        <a class="ghost-button" href="{{ route('login') }}">Login</a>
                        <a class="ghost-button" href="{{ route('register') }}">Signup</a>
                    @else
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="ghost-button" type="submit">Logout</button>
                        </form>
                    @endguest
                </div>
            </header>

            @if (session('error'))
                <div class="home-flash">{{ session('error') }}</div>
            @endif

            {{-- yep, this is the main home page, so the welcome screen stays public --}}

            <main>
                <section class="hero-grid">
                    <div class="hero-copy">
                        
                        <h2>{{ $heroMovie['title'] }}</h2>
                        <p>
                            {{ $heroMovie['description'] }}
                        </p>

                        <div class="hero-actions">
                            <a class="primary-button" href="#featured">Explore featured titles</a>
                            <a class="secondary-button" href="#catalog">View catalog overview</a>
                        </div>

                        @auth
                            <p class="tiny-note">
                                You are logged in as {{ auth()->user()->role }}.
                                @if (auth()->user()->role === 'admin')
                                    Use the admin CRUD links to manage movies.
                                @else
                                    Use the review page to rate movies.
                                @endif
                            </p>
                            @if (auth()->user()->role === 'user')
                                <div class="hero-actions">
                                    <a class="secondary-button" href="{{ route('reviews.create') }}">Write a review</a>
                                </div>
                            @endif
                        @else
                            
                        @endauth

                        <div class="genre-row" aria-label="Popular genres">
                            <span>{{ $heroMovie['genre'] }}</span>
                            <span>{{ $heroMovie['director'] }}</span>
                            <span>{{ $heroMovie['year'] }}</span>
                            <span>{{ $heroMovie['review_count'] }} reviews</span>
                        </div>

                        <div class="stat-grid">
                            @foreach ($stats as $stat)
                                <article class="stat-card">
                                    <strong>{{ $stat['value'] }}</strong>
                                    <span>{{ $stat['label'] }}</span>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <aside class="hero-poster" aria-label="Featured film spotlight">
                        <div class="poster-glow"></div>
                        <div class="poster-card">
                            <div class="poster-badge">Featured</div>
                            <div class="poster-art">
                                <div class="poster-orb orb-one"></div>
                                <div class="poster-orb orb-two"></div>
                                <div class="poster-orb orb-three"></div>
                                <div class="poster-frame"></div>
                            </div>
                            <div class="poster-meta">
                                <p class="poster-title">{{ $heroMovie['title'] }}</p>
                                <p class="poster-subtitle">{{ $heroMovie['genre'] }} · {{ $heroMovie['year'] }}</p>
                                <div class="poster-rating">
                                    <span>{{ $heroMovie['rating'] }}/10</span>
                                    <span>{{ $heroMovie['director'] }}</span>
                                </div>
                            </div>
                        </div>
                    </aside>
                </section>

                <section class="section-block" id="featured">
                    <div class="section-heading">
                        <div>
                            <p class="eyebrow">Featured movies</p>
                            <h3>Movie Cards</h3>
                        </div>
                        <a href="#catalog">See all entries</a>
                    </div>

                    <div class="movie-grid">
                        @forelse ($featuredMovies as $movie)
                            @php
                                $accent = match ($loop->index % 3) {
                                    0 => 'gold',
                                    1 => 'ember',
                                    default => 'rose',
                                };
                            @endphp
                            <article class="movie-card movie-card--{{ $accent }}">
                                <div class="movie-card__poster">
                                    <span>{{ $movie['year'] }}</span>
                                </div>
                                <div class="movie-card__body">
                                    <div class="movie-card__topline">
                                        <span>{{ $movie['genre'] }}</span>
                                        <strong>{{ $movie['rating'] }}</strong>
                                    </div>
                                    <h4>{{ $movie['title'] }}</h4>
                                    <p>{{ $movie['cast'] }}</p>
                                    <p>{{ $movie['director'] }}</p>
                                    <div class="movie-card__footer">
                                        <span>{{ $movie['review_count'] }} reviews</span>
                                        <span>{{ $movie['award_count'] }} awards</span>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <article class="movie-card movie-card--gold">
                                <div class="movie-card__poster">
                                    <span>Seed data</span>
                                </div>
                                <div class="movie-card__body">
                                    <h4>No movies yet</h4>
                                    <p>Add records to the movies table and seed the relations to populate this section.</p>
                                </div>
                            </article>
                        @endforelse
                    </div>
                </section>

                <section class="content-split" id="catalog">
                    <article class="panel panel--wide">
                        <div class="section-heading compact">
                            <div>
                                <p class="eyebrow">Catalog</p>
                            </div>
                        </div>

                        <form class="catalog-filter" method="GET" action="{{ route('home') }}">
                            <div>
                                <label for="genre_id">Genre</label>
                                <select id="genre_id" name="genre_id" onchange="this.form.submit()">
                                    <option value="">All genres</option>
                                    @foreach ($genres as $genre)
                                        <option value="{{ $genre->genre_id }}" @selected((string) $selectedGenreId === (string) $genre->genre_id)>{{ $genre->genre_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="movie_id">Movie</label>
                                <select id="movie_id" name="movie_id" onchange="this.form.submit()">
                                    <option value="">Choose a movie</option>
                                    @foreach ($catalogMovies as $movie)
                                        <option value="{{ $movie['movie_id'] }}" @selected((string) $selectedMovieId === (string) $movie['movie_id'])>{{ $movie['title'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="filter-actions">
                                <noscript>
                                    <button class="primary-button" type="submit">Apply filters</button>
                                </noscript>
                                <a class="secondary-button" href="{{ route('home') }}">Reset</a>
                            </div>
                        </form>

                        <div class="catalog-grid">
                            @forelse ($catalogMovies as $movie)
                                <article class="catalog-card {{ $selectedMovie && $selectedMovie['movie_id'] === $movie['movie_id'] ? 'catalog-card--active' : '' }}">
                                    <span class="eyebrow">{{ $movie['genre'] }}</span>
                                    <h4>{{ $movie['title'] }}</h4>
                                    <p>{{ $movie['director'] }}</p>
                                    @if ($movie['review_count'] > 0)
                                        <p>{{ $movie['year'] }} · {{ $movie['rating'] }}/10</p>
                                    @else
                                        <p>{{ $movie['year'] }} · No reviews yet</p>
                                    @endif
                                    <p>{{ $movie['review_count'] }} reviews</p>
                                </article>
                            @empty
                                <article class="catalog-card">
                                    <h4>No matching movies</h4>
                                    <p>Try another genre filter or clear the current selection.</p>
                                </article>
                            @endforelse
                        </div>

                        <div class="catalog-detail-grid">
                            <article class="panel">
                                <div class="section-heading compact">
                                    <div>
                                        <p class="eyebrow">Cast</p>
                                        <h3>{{ $selectedMovie ? $selectedMovie['title'] : 'Pick a movie' }}</h3>
                                    </div>
                                </div>

                                @if ($selectedMovie)
                                    <div class="detail-list">
                                        @forelse ($selectedCast as $castMember)
                                            <div class="detail-item">
                                                <strong>{{ $castMember->name }}</strong>
                                                <div class="tiny">{{ $castMember->role_name ?: 'No role listed' }}</div>
                                            </div>
                                        @empty
                                            <div class="detail-item">No cast data yet.</div>
                                        @endforelse
                                    </div>
                                @else
                                    <p class="muted">Select a movie to show its cast list.</p>
                                @endif
                            </article>

                            <article class="panel">
                                <div class="section-heading compact">
                                    <div>
                                        <p class="eyebrow">Reviews</p>
                                        <h3>{{ $selectedMovie ? $selectedMovie['title'] : 'Pick a movie' }}</h3>
                                    </div>
                                </div>

                                @if ($selectedMovie)
                                    <div class="detail-list">
                                        @forelse ($selectedReviews as $review)
                                            <div class="detail-item">
                                                <strong>{{ $review->reviewer_name ?? 'Anonymous reviewer' }}</strong>
                                                <div class="tiny">{{ number_format((float) $review->rating, 1) }} / 10</div>
                                                <p class="muted">{{ $review->comment ?: 'No comment provided.' }}</p>
                                            </div>
                                        @empty
                                            <div class="detail-item">No reviews yet for this movie.</div>
                                        @endforelse
                                    </div>
                                @else
                                    <p class="muted">Select a movie to show its reviews.</p>
                                @endif
                            </article>
                        </div>

                        <div class="catalog-list" style="margin-top: 18px;">
                            <div>
                                <strong>Top actors</strong>
                                <span>{{ implode(', ', $topActors ?: ['No actors yet']) }}</span>
                            </div>
                            <div>
                                <strong>Production companies</strong>
                                <span>{{ implode(', ', $productionCompanies ?: ['No companies yet']) }}</span>
                            </div>
                        </div>
                    </article>
                    


                    <aside class="panel panel--side" id="awards">
                        <p class="eyebrow">Awards spotlight</p>
                        <h3>Built for the prestige layer.</h3>
                        <ul class="award-list">
                            @forelse ($awardSpotlight as $award)
                                <li>{{ $award }}</li>
                            @empty
                                <li>No awards yet</li>
                            @endforelse
                        </ul>
                    </aside>
                </section>

                <section class="section-block" id="actor-leaderboard">
                    <div class="section-heading">
                        <h3>Top Actors Leaderboard</h3>
                    </div>
                    <ul>
                        @forelse ($actorRankings as $actor)
                            <li>
                                {{ $actor->actor_name }} — 
                                {{ $actor->movie_count }} movies, 
                                {{ $actor->award_count }} awards
                            </li>
                        @empty
                            <li>No actor rankings yet.</li>
                        @endforelse
                    </ul>
                    </section>

                <section class="section-block" id="reviews">
                    <div class="section-heading">
                        <div>
                            <p class="eyebrow">Reviews</p>
                            
                        </div>
                    </div>

                    <section class="content-split" id="recent-reviews">
                        <article class="panel panel--wide">
                            <div class="section-heading compact">
                                <div>
                                    <p class="eyebrow">Recent Reviews</p>
                                </div>
                            </div>

                            <div class="catalog-grid">
                                @forelse ($recentReviews as $review)
                                    <article class="catalog-card">
                                        <h4>{{ $review->movie_title }} ({{ $review->release_year }})</h4>
                                        <p>Rating: {{ number_format($review->rating, 1) }}/10</p>
                                        <p>"{{ $review->comment }}"</p>
                                        <span class="eyebrow">Reviewed on {{ \Carbon\Carbon::parse($review->created_at)->format('M d, Y') }}</span>
                                    </article>
                                @empty
                                    <article class="catalog-card">
                                        <h4>No reviews yet</h4>
                                        <p>Be the first to leave a review!</p>
                                    </article>
                                @endforelse
                            </div>
                        </article>
                    </section>

                </section>
            </main>
        </div>
    </body>
</html>