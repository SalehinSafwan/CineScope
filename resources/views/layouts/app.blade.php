<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CineDB')</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #0f172a;
            --panel: #111827;
            --panel-soft: #1f2937;
            --text: #e5e7eb;
            --muted: #9ca3af;
            --accent: #f59e0b;
            --accent-2: #fb7185;
            --line: rgba(255,255,255,.12);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: radial-gradient(circle at top, #1e293b 0%, var(--bg) 52%);
            color: var(--text);
        }
        a { color: inherit; text-decoration: none; }
        .wrap {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
            padding: 32px 0 48px;
        }
        .topbar, .card, .panel, .table-shell, .form-shell {
            background: rgba(17,24,39,.92);
            border: 1px solid var(--line);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,.28);
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: center;
            padding: 18px 22px;
            margin-bottom: 24px;
        }
        .brand { font-weight: 800; letter-spacing: .04em; }
        .brand small { display: block; color: var(--muted); font-weight: 400; }
        .nav { display: flex; gap: 10px; flex-wrap: wrap; }
        .btn, .btn-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 0;
            border-radius: 999px;
            padding: 11px 16px;
            font-weight: 700;
            cursor: pointer;
        }
        .btn { background: var(--accent); color: #111827; }
        .btn-link { background: transparent; color: var(--text); border: 1px solid var(--line); }
        .grid { display: grid; gap: 18px; }
        .table-shell, .form-shell { padding: 22px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px 12px; border-bottom: 1px solid var(--line); text-align: left; vertical-align: top; }
        th { color: var(--muted); font-size: 13px; text-transform: uppercase; letter-spacing: .08em; }
        .muted { color: var(--muted); }
        .flash {
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 14px;
            background: rgba(245,158,11,.12);
            border: 1px solid rgba(245,158,11,.24);
            color: #fde68a;
        }
        .field { display: grid; gap: 8px; margin-bottom: 16px; }
        input, select, textarea {
            width: 100%;
            background: #0b1220;
            color: var(--text);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 12px 14px;
            font: inherit;
        }
        textarea { min-height: 140px; resize: vertical; }
        .actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .tiny { font-size: 13px; color: var(--muted); }
        @media (max-width: 720px) {
            .topbar { flex-direction: column; align-items: flex-start; }
            table, thead, tbody, th, td, tr { display: block; }
            thead { display: none; }
            tr { padding: 12px 0; }
            td { border-bottom: 0; padding: 8px 0; }
            td::before { content: attr(data-label) ": "; color: var(--muted); font-weight: 700; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="wrap">
    <header class="topbar">
        <div class="brand">
            CineScope
        </div>

        <nav class="nav">
            <a class="btn-link" href="{{ route('home') }}">Home</a>

            @auth
                @if (auth()->user()->role === 'admin')
                    <a class="btn-link" href="{{ route('movies.index') }}">Movies</a>
                    <a class="btn" href="{{ route('movies.create') }}">Add movie</a>
                @endif

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn-link" type="submit">Logout</button>
                </form>
            @else
                <a class="btn-link" href="{{ route('login') }}">Login</a>
                <a class="btn" href="{{ route('register') }}">Signup</a>
            @endauth
        </nav>
    </header>

    @if (session('error'))
        <div class="flash">{{ session('error') }}</div>
    @endif

    @if (session('success'))
        <div class="flash">{{ session('success') }}</div>
    @endif

    @yield('content')
</div>
</body>
</html>