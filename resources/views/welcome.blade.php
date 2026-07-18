<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CineScope</title>
    <meta name="description" content="CineDB is a cinematic homepage for exploring movies, directors, genres, cast, awards, production companies, and reviews.">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700|playfair-display:700,800" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        /* Classy Sapphire & Platinum Palette */
        :root {
            --bg-main: #0b0f19;
            --bg-card: rgba(22, 30, 49, 0.65);
            --border-glow: rgba(32, 106, 197, 0.25);
            --text-muted: #94a3b8;
            --accent-blue: #3b82f6;
            --accent-cyan: #60a5fa;
            --text-platinum: #f8fafc;
        }

        body.cine-home {
            background-color: var(--bg-main);
            color: #e2e8f0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.01em;
        }

        /* Flash Messages */
        .home-flash {
            margin: 20px 0;
            padding: 14px 20px;
            border-radius: 14px;
            background: linear-gradient(90deg, rgba(32, 106, 197, 0.15), rgba(32, 106, 197, 0.03));
            border-left: 4px solid var(--accent-blue);
            color: #93c5fd;
            font-weight: 500;
        }

        /* Text Gradient Utility for Headings */
        .gradient-headline {
            background: linear-gradient(135deg, #ffffff 0%, #206ac5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-block;
        }

        /* Sophisticated Forms & Dropdowns */
        .catalog-filter {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            margin: 24px 0 32px;
            background: rgba(255, 255, 255, 0.01);
            padding: 24px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.04);
        }

        .catalog-filter label {
            display: block;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 8px;
            font-weight: 600;
        }

        .catalog-filter select {
            width: 100%;
            padding: 12px 16px;
            background: #131a2c;
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #f1f5f9;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            outline: none;
        }

        .catalog-filter select:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .catalog-filter .filter-actions {
            grid-column: 1 / -1;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        /* Elegant Cards & Glassmorphism */
        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-top: 24px;
        }

        .catalog-card {
            padding: 24px;
            border-radius: 20px;
            background: var(--bg-card);
            border: 1px solid rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(8px);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .catalog-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, rgba(255,255,255,0.1), transparent);
        }

        .catalog-card:hover {
            transform: translateY(-4px);
            border-color: rgba(59, 130, 246, 0.35);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5), 0 0 20px var(--border-glow);
        }

        .catalog-card--active {
            border-color: var(--accent-cyan);
            background: linear-gradient(165deg, rgba(22, 30, 49, 0.9), rgba(32, 106, 197, 0.08));
            box-shadow: 0 0 25px rgba(32, 106, 197, 0.15);
        }

        .catalog-card h4 {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 8px 0 12px;
            color: #ffffff;
        }

        .catalog-card p {
            margin: 0 0 8px;
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* Detail Grid & Subsections */
        .catalog-detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px;
            margin-top: 32px;
        }

        .detail-list {
            display: grid;
            gap: 12px;
            margin-top: 16px;
        }

        .detail-item {
            padding: 16px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.01);
            border: 1px solid rgba(255, 255, 255, 0.03);
            transition: background 0.2s;
        }

        .detail-item:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        .catalog-list {
            background: rgba(255, 255, 255, 0.01);
            padding: 20px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.04);
        }

        /* Typography Utilities */
        .eyebrow {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--accent-cyan);
            font-weight: 700;
        }

        .tiny-note {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 20px;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .catalog-filter, .catalog-detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="cine-home">
    <div class="page-shell">
        <header class="topbar">
            <div class="brand-lockup">
                <div class="brand-mark" style="background: linear-gradient(135deg, #ffffff, #206ac5); color: #fff; font-weight: 800;">C</div>
                <div>
                    <p class="eyebrow">Movie Catalog</p>
                    <h1 class="gradient-headline" style="font-family: 'Playfair Display', serif; font-weight: 800;">CineScope</h1>
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
                    <a class="ghost-button" href="{{ route('register') }}" style="border-color: var(--accent-cyan); color: var(--accent-cyan);">Signup</a>
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

        <main>
            <section class="hero-grid">
                <div class="hero-copy">
                    <h2 class="gradient-headline" style="font-family: 'Playfair Display', serif; font-size: 3.5rem; line-height: 1.1; margin-bottom: 16px;">{{ $heroMovie['title'] }}</h2>
                    <p style="font-size: 1.1rem; color: #cbd5e1; line-height: 1.6; margin-bottom: 24px;">{{ $heroMovie['description'] }}</p>

                    <div class="hero-actions">
                        <a class="primary-button" href="#featured" style="background: linear-gradient(135deg, #ffffff 0%, #206ac5 100%); color: #0b0f19; font-weight: 700; border: none;">Explore Featured</a>
                        <a class="secondary-button" href="#catalog">View Catalog</a>
                    </div>

                    @auth
                        <p class="tiny-note">
                            Logged in as <span style="color: #fff; font-weight: 600;">{{ auth()->user()->role }}</span>.
                            @if (auth()->user()->role === 'admin')
                                Use the admin dashboard to manage listings.
                            @else
                                Share your cinematic thoughts below.
                            @endif
                        </p>
                        @if (auth()->user()->role === 'user')
                            <div class="hero-actions" style="margin-top: 12px;">
                                <a class="secondary-button" href="{{ route('reviews.create') }}">Write a review</a>
                            </div>
                        @endif
                    @endauth

                    <div class="genre-row" aria-label="Popular genres" style="margin-top: 30px; gap: 16px;">
                        <span style="background: rgba(255,255,255,0.04); padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; color: #f1f5f9;">{{ $heroMovie['genre'] }}</span>
                        <span style="background: rgba(255,255,255,0.04); padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; color: #f1f5f9;">{{ $heroMovie['director'] }}</span>
                        <span style="background: rgba(255,255,255,0.04); padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; color: #f1f5f9;">{{ $heroMovie['year'] }}</span>
                        <span style="color: var(--accent-cyan);">💎 {{ $heroMovie['review_count'] }} reviews</span>
                    </div>

                    <div class="stat-grid" style="margin-top: 40px;">
                        @foreach ($stats as $stat)
                            <article class="stat-card" style="background: transparent; border-left: 2px solid rgba(255,255,255,0.08); padding-left: 16px;">
                                <strong style="font-size: 2rem; color: #fff;">{{ $stat['value'] }}</strong>
                                <span style="color: var(--text-muted); font-size: 0.85rem;">{{ $stat['label'] }}</span>
                            </article>
                        @endforeach
                    </div>
                </div>

                <aside class="hero-poster" aria-label="Featured film spotlight">
                    <div class="poster-glow" style="background: radial-gradient(circle, rgba(32, 106, 197, 0.2) 0%, transparent 70%);"></div>
                    <div class="poster-card" style="border: 1px solid rgba(255,255,255,0.06); background: #131a2c;">
                        <div class="poster-badge" style="background: linear-gradient(135deg, #ffffff, #206ac5); color: #0b0f19; font-weight: 700;">Spotlight</div>
                        <div class="poster-art">
                            <div class="poster-orb orb-one" style="background: rgba(32, 106, 197, 0.2);"></div>
                            <div class="poster-orb orb-two"></div>
                            <div class="poster-frame"></div>
                        </div>
                        <div class="poster-meta" style="background: linear-gradient(to top, #0b0f19 80%, transparent);">
                            <p class="poster-title" style="font-family: 'Playfair Display', serif;">{{ $heroMovie['title'] }}</p>
                            <p class="poster-subtitle">{{ $heroMovie['genre'] }} · {{ $heroMovie['year'] }}</p>
                            <div class="poster-rating">
                                <span style="color: var(--accent-cyan);">✦ {{ $heroMovie['rating'] }}/10</span>
                                <span>{{ $heroMovie['director'] }}</span>
                            </div>
                        </div>
                    </div>
                </aside>
            </section>

            <section class="section-block" id="featured" style="margin-top: 80px;">
                <div class="section-heading">
                    <div>
                        <p class="eyebrow">Curated Selection</p>
                        <h3 class="gradient-headline" style="font-family: 'Playfair Display', serif; font-size: 2.2rem;">Featured Masterpieces</h3>
                    </div>
                    <a href="#catalog" style="color: var(--accent-cyan); font-weight: 600;">Explore Archive →</a>
                </div>

                <div class="movie-grid">
                    @forelse ($featuredMovies as $movie)
                        <article class="movie-card" style="background: var(--bg-card); border: 1px solid rgba(255,255,255,0.04); border-radius: 20px;">
                            <div class="movie-card__poster" style="background: linear-gradient(45deg, #161e31, #0f1524);">
                                <span style="font-weight: 700; color: #f1f5f9;">{{ $movie['year'] }}</span>
                            </div>
                            <div class="movie-card__body">
                                <div class="movie-card__topline">
                                    <span class="eyebrow" style="font-size: 0.7rem;">{{ $movie['genre'] }}</span>
                                    <strong style="color: var(--accent-cyan);">✦ {{ $movie['rating'] }}</strong>
                                </div>
                                <h4 style="font-size: 1.3rem; margin: 8px 0; color: #fff;">{{ $movie['title'] }}</h4>
                                <p style="font-size: 0.85rem; color: #cbd5e1;">Cast: {{ $movie['cast'] }}</p>
                                <p style="font-size: 0.85rem; color: var(--text-muted);">Direction: {{ $movie['director'] }}</p>
                                <div class="movie-card__footer" style="border-top: 1px solid rgba(255,255,255,0.04); padding-top: 12px; margin-top: 12px;">
                                    <span>{{ $movie['review_count'] }} Reviews</span>
                                    <span>{{ $movie['award_count'] }} Laurels</span>
                                </div>
                            </div>
                        </article>
                    @empty
                        <article class="movie-card">
                            <div class="movie-card__body">
                                <h4>No entries found</h4>
                                <p>Seed the database archive to populate this view.</p>
                            </div>
                        </article>
                    @endforelse
                </div>
            </section>

            <section class="content-split" id="catalog" style="margin-top: 80px;">
                <article class="panel panel--wide" style="background: transparent; padding: 0;">
                    <div class="section-heading compact">
                        <div>
                            <p class="eyebrow">Search Engine</p>
                            <h3 class="gradient-headline" style="font-family: 'Playfair Display', serif; font-size: 2.2rem;">The Complete Archive</h3>
                        </div>
                    </div>

                    <form class="catalog-filter" method="GET" action="{{ route('home') }}">
                        <div>
                            <label for="genre_id">Filter By Genre</label>
                            <select id="genre_id" name="genre_id" onchange="this.form.submit()">
                                <option value="">All Cinema Genres</option>
                                @foreach ($genres as $genre)
                                    <option value="{{ $genre->genre_id }}" @selected((string) $selectedGenreId === (string) $genre->genre_id)>{{ $genre->genre_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="movie_id">Inspect Direct Title</label>
                            <select id="movie_id" name="movie_id" onchange="this.form.submit()">
                                <option value="">Select an entry...</option>
                                @foreach ($catalogMovies as $movie)
                                    <option value="{{ $movie['movie_id'] }}" @selected((string) $selectedMovieId === (string) $movie['movie_id'])>{{ $movie['title'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-actions">
                            <noscript>
                                <button class="primary-button" type="submit">Filter</button>
                            </noscript>
                            <a class="secondary-button" href="{{ route('home') }}" style="border-radius: 12px;">Reset Filters</a>
                        </div>
                    </form>

                    <div class="catalog-grid">
                        @forelse ($catalogMovies as $movie)
                            <article class="catalog-card {{ $selectedMovie && $selectedMovie['movie_id'] === $movie['movie_id'] ? 'catalog-card--active' : '' }}">
                                <span class="eyebrow" style="font-size: 0.7rem;">{{ $movie['genre'] }}</span>
                                <h4>{{ $movie['title'] }}</h4>
                                <p style="color: #fff;">{{ $movie['director'] }}</p>
                                <p>{{ $movie['year'] }} · {{ $movie['review_count'] > 0 ? $movie['rating'] . '/10' : 'Unrated' }}</p>
                            </article>
                        @empty
                            <article class="catalog-card">
                                <h4>No items match criteria</h4>
                            </article>
                        @endforelse
                    </div>

                    <div class="catalog-detail-grid">
                        <article class="panel" style="background: var(--bg-card); border-radius: 20px; padding: 24px; border: 1px solid rgba(255,255,255,0.04);">
                            <div class="section-heading compact">
                                <div>
                                    <p class="eyebrow">Credits</p>
                                    <h4 style="margin: 4px 0 0; color: #fff;">{{ $selectedMovie ? $selectedMovie['title'] : 'Ensemble Cast' }}</h4>
                                </div>
                            </div>

                            @if ($selectedMovie)
                                <div class="detail-list">
                                    @forelse ($selectedCast as $castMember)
                                        <div class="detail-item">
                                            <strong style="color: #fff;">{{ $castMember->name }}</strong>
                                            <div style="color: var(--accent-cyan); font-size: 0.8rem; margin-top: 4px;">{{ $castMember->role_name ?: 'Performer' }}</div>
                                        </div>
                                    @empty
                                        <div class="detail-item">No records listed.</div>
                                    @endforelse
                                </div>
                            @else
                                <p class="muted" style="margin-top: 16px;">Select an archive film to display billing configuration.</p>
                            @endif
                        </article>

                        <article class="panel" style="background: var(--bg-card); border-radius: 20px; padding: 24px; border: 1px solid rgba(255,255,255,0.04);">
                            <div class="section-heading compact">
                                <div>
                                    <p class="eyebrow">Critical Reception</p>
                                    <h4 style="margin: 4px 0 0; color: #fff;">{{ $selectedMovie ? $selectedMovie['title'] : 'User Reviews' }}</h4>
                                </div>
                            </div>

                            @if ($selectedMovie)
                                <div class="detail-list">
                                    @forelse ($selectedReviews as $review)
                                        <div class="detail-item">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                                <strong style="color: #fff;">{{ $review->reviewer_name ?? 'Anonymous User' }}</strong>
                                                <span style="color: var(--accent-cyan); font-size: 0.85rem; font-weight: 700;">✦ {{ number_format((float) $review->rating, 1) }}</span>
                                            </div>
                                            <p style="margin: 0; font-size: 0.85rem; font-style: italic; color: #cbd5e1;">"{{ $review->comment ?: 'No description provided.' }}"</p>
                                        </div>
                                    @empty
                                        <div class="detail-item">This title hasn't been evaluated yet.</div>
                                    @endforelse
                                </div>
                            @else
                                <p class="muted" style="margin-top: 16px;">Select an archive film to display runtime commentary logs.</p>
                            @endif
                        </article>
                    </div>

                    <div class="catalog-list" style="margin-top: 32px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                        <div>
                            <p class="eyebrow" style="margin-bottom: 6px;">Prominent Billing</p>
                            <span style="color: #cbd5e1; font-size: 0.95rem;">{{ implode(', ', $topActors ?: ['Awaiting ledger updates']) }}</span>
                        </div>
                        <div>
                            <p class="eyebrow" style="margin-bottom: 6px;">Studio Guilds</p>
                            <span style="color: #cbd5e1; font-size: 0.95rem;">{{ implode(', ', $productionCompanies ?: ['Independent release architectures']) }}</span>
                        </div>
                    </div>
                </article>

                <aside class="panel panel--side" id="awards" style="background: linear-gradient(185deg, var(--bg-card), rgba(0,0,0,0.1)); border-radius: 20px; border: 1px solid rgba(255,255,255,0.04); padding: 24px;">
                    <p class="eyebrow">Laurel System</p>
                    <h3 class="gradient-headline" style="font-family: 'Playfair Display', serif; margin-bottom: 20px;">Prestige Trackers</h3>
                    <ul class="award-list" style="list-style: none; padding: 0; display: grid; gap: 12px;">
                        @forelse ($awardSpotlight as $award)
                            <li style="background: rgba(255,255,255,0.01); padding: 12px; border-radius: 10px; border-left: 2px solid var(--accent-blue); font-size: 0.9rem;">{{ $award }}</li>
                        @empty
                            <li style="color: var(--text-muted);">No accolades indexed yet.</li>
                        @endforelse
                    </ul>
                </aside>
            </section>

            <section class="section-block" id="actor-leaderboard" style="margin-top: 80px; background: rgba(255,255,255,0.005); padding: 32px; border-radius: 24px; border: 1px solid rgba(255,255,255,0.02);">
                <div class="section-heading">
                    <h3 class="gradient-headline" style="font-family: 'Playfair Display', serif; font-size: 2rem;">Industry Leaderboards</h3>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-top: 20px;">
                    @forelse ($actorRankings as $actor)
                        <div style="background: var(--bg-card); padding: 16px; border-radius: 14px; border: 1px solid rgba(255,255,255,0.04);">
                            <strong style="color: #fff; display: block; margin-bottom: 4px;">{{ $actor->actor_name }}</strong>
                            <div style="font-size: 0.85rem; color: var(--text-muted);">
                                {{ $actor->movie_count }} Features · <span style="color: var(--accent-cyan);">{{ $actor->award_count }} Laurels</span>
                            </div>
                        </div>
                    @empty
                        <p class="muted">Rankings compute engine offline.</p>
                    @endforelse
                </div>
            </section>

            <section class="section-block" id="reviews" style="margin-top: 80px;">
                <div class="section-heading">
                    <div>
                        <p class="eyebrow">Realtime Feed</p>
                        <h3 class="gradient-headline" style="font-family: 'Playfair Display', serif; font-size: 2rem;">Recent Audience Critiques</h3>
                    </div>
                </div>

                <div class="catalog-grid">
                    @forelse ($recentReviews as $review)
                        <article class="catalog-card">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                                <h4 style="margin: 0; font-size: 1.1rem;">{{ $review->movie_title }}</h4>
                                <span style="color: var(--accent-cyan); font-weight: 700;">✦ {{ number_format($review->rating, 1) }}</span>
                            </div>
                            <p style="font-size: 0.85rem; font-style: italic; color: #e2e8f0; margin-bottom: 16px;">"{{ $review->comment }}"</p>
                            <span class="eyebrow" style="font-size: 0.65rem; color: var(--text-muted);">Logged {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
                        </article>
                    @empty
                        <article class="catalog-card">
                            <p class="muted">No audience commentary data stream available.</p>
                        </article>
                    @endforelse
                </div>
            </section>

            <section class="section-block" id="movie-anniversaries" style="margin-top: 80px; margin-bottom: 80px;">
                <div class="section-heading">
                    <div>
                        <p class="eyebrow">Historical Milestones</p>
                        <h3 class="gradient-headline" style="font-family: 'Playfair Display', serif; font-size: 2rem;">Upcoming Release Anniversaries</h3>
                    </div>
                </div>

                <div class="catalog-grid">
                    @forelse ($anniversaries as $movie)
                        <article class="catalog-card" style="border-left: 3px solid var(--accent-blue);">
                            <h4 style="margin: 0 0 6px 0;">{{ $movie->title }}</h4>
                            <p style="color: #fff; font-weight: 600; font-size: 1.1rem; margin: 0;">{{ $movie->years_since }} Year Anniversary</p>
                            <span class="eyebrow" style="font-size: 0.65rem; color: var(--text-muted);">Premiered in {{ $movie->release_year }}</span>
                        </article>
                    @empty
                        <article class="catalog-card">
                            <h4>No immediate records found</h4>
                        </article>
                    @endforelse
                </div>
            </section>
        </main>
    </div>
</body>
</html>