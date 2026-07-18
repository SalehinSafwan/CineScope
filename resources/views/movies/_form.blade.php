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

@if (! isset($movie))
    <div class="field">
        <label>Need a new director?</label>
        <button class="btn-link" type="button" data-open-director-dialog>ADD A NEW DIRECTOR</button>
        <div class="tiny">You can keep the existing director list above or create a new one with name, birth year, and nationality.</div>
    </div>

    <dialog class="director-dialog" id="director-dialog">
        <div class="director-dialog__header">
            <h2 style="margin: 0;">Add new director</h2>
            <button class="btn-link" type="button" data-close-director-dialog>Close</button>
        </div>

        <div class="field">
            <label for="new_director_name">Name</label>
            <input id="new_director_name" name="new_director_name" value="{{ old('new_director_name') }}" type="text" placeholder="Director name">
            @error('new_director_name')<div class="tiny">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="new_director_birth_year">Birth year</label>
            <input id="new_director_birth_year" name="new_director_birth_year" value="{{ old('new_director_birth_year') }}" type="number" min="1900" max="{{ date('Y') }}" placeholder="1984">
            @error('new_director_birth_year')<div class="tiny">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="new_director_nationality">Nationality</label>
            <input id="new_director_nationality" name="new_director_nationality" value="{{ old('new_director_nationality') }}" type="text" placeholder="Nigerian">
            @error('new_director_nationality')<div class="tiny">{{ $message }}</div>@enderror
        </div>
    </dialog>
@endif

<div class="field">
    <label for="production_company_id">Production Company</label>
    <select id="production_company_id" name="production_company_id">
        <option value="">Pick one</option>
        @foreach ($productionCompanies as $company)
            <option value="{{ $company->production_company_id }}" @selected(old('production_company_id', $movie->production_company_id ?? '') == $company->production_company_id)>{{ $company->name }}</option>
        @endforeach
    </select>
    @error('production_company_id')<div class="tiny">{{ $message }}</div>@enderror
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
