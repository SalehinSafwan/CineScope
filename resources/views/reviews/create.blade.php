@extends('layouts.app')

@section('title', 'Write review')

@push('styles')
    <style>
        .review-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(280px, 0.8fr);
            gap: 18px;
            align-items: start;
        }

        .review-card {
            padding: 22px;
        }

        .review-list {
            display: grid;
            gap: 12px;
        }

        .review-item {
            padding: 14px;
            border-radius: 14px;
            background: #0b1220;
            border: 1px solid var(--line);
        }

        .review-item strong,
        .review-item p {
            display: block;
            margin: 0 0 8px;
        }

        @media (max-width: 920px) {
            .review-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="review-layout">
        <section class="form-shell review-card">
            <h1 style="margin-top: 0;">Write a review</h1>
            <p class="tiny">Pick a movie from the list, choose a half-star rating, and add your comment.</p>

            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf

                <div class="field">
                    <label for="movie_id">Movie</label>
                    <select id="movie_id" name="movie_id" required>
                        <option value="">Choose a movie</option>
                        @foreach ($movies as $movie)
                            <option value="{{ $movie->movie_id }}" @selected(old('movie_id') == $movie->movie_id)>
                                {{ $movie->title }} ({{ $movie->release_year }})
                            </option>
                        @endforeach
                    </select>
                    @error('movie_id')<div class="tiny">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="rating">Rating</label>
                    <input
                        id="rating"
                        name="rating"
                        type="number"
                        min="0"
                        max="10"
                        step="0.1"
                        inputmode="decimal"
                        value="{{ old('rating', '5.0') }}"
                        required
                    >
                    <div class="tiny">0.0-10.0</div>
                    @error('rating')<div class="tiny">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="comment">Review text</label>
                    <textarea id="comment" name="comment" placeholder="Tell us what worked, what did not, and why.">{{ old('comment') }}</textarea>
                    @error('comment')<div class="tiny">{{ $message }}</div>@enderror
                </div>

                <div class="actions">
                    <button class="btn" type="submit">Save review</button>
                    <a class="btn-link" href="{{ route('home') }}">Back home</a>
                </div>
            </form>
        </section>

        <aside class="form-shell review-card">
            <h2 style="margin-top: 0;">Your recent reviews</h2>
            <p class="tiny">Each review is saved with your user id and the selected movie id.</p>

            <div class="review-list">
                @forelse ($recentReviews as $review)
                    <article class="review-item">
                        <strong>{{ $review->movie?->title ?? 'Deleted movie' }}</strong>
                        <p>{{ number_format((float) $review->rating, 1) }} stars</p>
                        <p class="muted">{{ $review->comment ?: 'No comment provided.' }}</p>
                    </article>
                @empty
                    <p class="muted">No reviews yet. Submit the first one from the form.</p>
                @endforelse
            </div>
        </aside>
    </div>
@endsection