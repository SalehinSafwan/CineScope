@extends('layouts.app')

@section('title', 'Movies')

@section('content')
    <div class="table-shell">
        <div class="actions" style="justify-content: space-between; margin-bottom: 18px;">
            <div>
                <h1 style="margin: 0 0 6px;">All movies</h1>
            </div>
            <a class="btn" href="{{ route('movies.create') }}">Create movie</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Year</th>
                    <th>Rating</th>
                    <th>Language</th>
                    <th>Director</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($movies as $movie)
                    <tr>
                        <td data-label="Title">{{ $movie->title }}</td>
                        <td data-label="Year">{{ $movie->release_year }}</td>
                        <td data-label="Rating">{{ number_format((float) $movie->rating, 1) }}</td>
                        <td data-label="Language">{{ $movie->language }}</td>
                        <td data-label="Director">{{ $movie->director?->name ?? 'No director picked' }}</td>
                        <td data-label="Description" class="muted">{{ $movie->description ?: 'No description yet.' }}</td>
                        <td data-label="Actions">
                            <div class="actions">
                                <a class="btn-link" href="{{ route('movies.edit', $movie) }}">Edit</a>

                                <form action="{{ route('movies.destroy', $movie) }}" method="POST" onsubmit="return confirm('Delete this movie?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-link" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="muted">No movies found. Seed the database or add one manually.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection