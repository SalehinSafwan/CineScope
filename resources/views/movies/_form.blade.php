@csrf

<div class="field">
    <label for="title">Title</label>
    <input id="title" name="title" value="{{ old('title', $movie->title ?? '') }}" type="text" required>
    @error('title')<div class="tiny">{{ $message }}</div>@enderror
</div>

<div class="field">
    <label for="release_year">Release year</label>
    <input id="release_year" name="release_year" value="{{ old('release_year', $movie->release_year ?? '') }}" type="number" min="1900" max="{{ date('Y') + 1 }}" required>
    @error('release_year')<div class="tiny">{{ $message }}</div>@enderror
</div>

<div class="field">
    <label for="rating">Rating</label>
    <input id="rating" name="rating" value="{{ old('rating', $movie->rating ?? '0.0') }}" type="number" step="0.1" min="0" max="10" required>
    @error('rating')<div class="tiny">{{ $message }}</div>@enderror
</div>

<div class="field">
    <label for="language">Language</label>
    <input id="language" name="language" value="{{ old('language', $movie->language ?? 'English') }}" type="text" required>
    @error('language')<div class="tiny">{{ $message }}</div>@enderror
</div>

<div class="field">
    <label for="director_id">Director</label>
    <select id="director_id" name="director_id">
        <option value="">Pick one or leave blank</option>
        @foreach ($directors as $director)
            <option value="{{ $director->director_id }}" @selected(old('director_id', $movie->director_id ?? '') == $director->director_id)>{{ $director->name }}</option>
        @endforeach
    </select>
    @error('director_id')<div class="tiny">{{ $message }}</div>@enderror
</div>

<div class="field">
    <label for="poster_url">Poster URL</label>
    <input id="poster_url" name="poster_url" value="{{ old('poster_url', $movie->poster_url ?? '') }}" type="text" placeholder="https://...">
    @error('poster_url')<div class="tiny">{{ $message }}</div>@enderror
</div>

<div class="field">
    <label for="description">Description</label>
    <textarea id="description" name="description">{{ old('description', $movie->description ?? '') }}</textarea>
    @error('description')<div class="tiny">{{ $message }}</div>@enderror
</div>

<div class="actions">
    <button class="btn" type="submit">{{ $buttonText }}</button>
    <a class="btn-link" href="{{ route('movies.index') }}">Cancel</a>
</div>