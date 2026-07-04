@extends('layouts.app')

@section('title', 'Create movie')

@push('styles')
    <style>
        .director-dialog {
            width: min(560px, calc(100vw - 32px));
            border: 1px solid var(--line);
            border-radius: 20px;
            background: rgba(15, 23, 42, 0.98);
            color: var(--text);
            padding: 20px;
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.45);
        }

        .director-dialog::backdrop {
            background: rgba(2, 6, 23, 0.7);
            backdrop-filter: blur(4px);
        }

        .director-dialog__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }
    </style>
@endpush

@section('content')
    <div class="form-shell">
        <h1 style="margin-top: 0;">Create movie</h1>
        <p class="tiny">This is the form for adding a brand new movie record.</p>

        <form action="{{ route('movies.store') }}" method="POST">
            @include('movies._form', ['buttonText' => 'Save movie'])
        </form>

        <script>
            const openDirectorDialogButton = document.querySelector('[data-open-director-dialog]');
            const directorDialog = document.getElementById('director-dialog');
            const closeDirectorDialogButton = document.querySelector('[data-close-director-dialog]');

            if (openDirectorDialogButton && directorDialog) {
                openDirectorDialogButton.addEventListener('click', () => directorDialog.showModal());
            }

            if (closeDirectorDialogButton && directorDialog) {
                closeDirectorDialogButton.addEventListener('click', () => directorDialog.close());
            }

            if (directorDialog && ({{ $errors->has('new_director_name') ? 'true' : 'false' }} || {{ $errors->has('new_director_birth_year') ? 'true' : 'false' }} || {{ $errors->has('new_director_nationality') ? 'true' : 'false' }})) {
                directorDialog.showModal();
            }
        </script>
    </div>
@endsection