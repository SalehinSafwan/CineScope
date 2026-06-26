@extends('layouts.app')

@section('title', 'Edit movie')

@section('content')
    <div class="form-shell">
        <h1 style="margin-top: 0;">Edit movie</h1>
        <p class="tiny">This page changes the movie that is already saved.</p>

        <form action="{{ route('movies.update', $movie) }}" method="POST">
            @method('PUT')
            @include('movies._form', ['buttonText' => 'Update movie'])
        </form>
    </div>
@endsection