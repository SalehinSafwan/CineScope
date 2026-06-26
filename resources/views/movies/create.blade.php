@extends('layouts.app')

@section('title', 'Create movie')

@section('content')
    <div class="form-shell">
        <h1 style="margin-top: 0;">Create movie</h1>
        <p class="tiny">This is the form for adding a brand new movie record.</p>

        <form action="{{ route('movies.store') }}" method="POST">
            @include('movies._form', ['buttonText' => 'Save movie'])
        </form>
    </div>
@endsection