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
                            <a href="{{ route('movies.index') }}">Admin CRUD</a>
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
                                        <span>{{ $movie['awards'] }}</span>
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

                        <div class="catalog-list">
                            <div>
                                <strong>Movies</strong>
                             
                            </div>
                            <div>
                                <strong>Cast</strong>
                                
                            </div>
                            <div>
                                <strong>Community</strong>
                              
                            </div>
                            <div>
                                <strong>Business data</strong>
                               
                            </div>
                        </div>

                        <div class="catalog-list">
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

                <section class="section-block" id="reviews">
                    <div class="section-heading">
                        <div>
                            <p class="eyebrow">Reviews</p>
                            
                        </div>
                    </div>

                    <div class="review-strip">
                        <article>
                            <p>"Sharp, moody, and easy to navigate. The film-first layout feels premium."</p>
                            <span>Critic note</span>
                        </article>
                        <article>
                            <p>"Perfect for browsing casts, production teams, and award history without clutter."</p>
                            <span>Audience note</span>
                        </article>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>