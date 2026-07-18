<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CineScope')</title>
    <style>
        /* Classy Sapphire & Platinum Corporate Architecture */
        :root {
            color-scheme: dark;
            --bg: #0b0f19;
            --panel: rgba(22, 30, 49, 0.65);
            --panel-soft: rgba(30, 41, 59, 0.5);
            --text: #f8fafc;
            --muted: #94a3b8;
            --accent: #206ac5;
            --accent-hover: #1d4ed8;
            --accent-glow: rgba(32, 106, 197, 0.25);
            --accent-2: #f43f5e;
            --line: rgba(255, 255, 255, 0.05);
            --font-stack: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        * { 
            box-sizing: border-box; 
        }

        body {
            margin: 0;
            font-family: var(--font-stack);
            background: radial-gradient(circle at top center, #131a2c 0%, var(--bg) 70%);
            color: var(--text);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            letter-spacing: -0.01em;
        }

        a { 
            color: inherit; 
            text-decoration: none; 
            transition: color 0.2s ease, opacity 0.2s ease;
        }

        .wrap {
            width: min(1200px, calc(100% - 40px));
            margin: 0 auto;
            padding: 40px 0 60px;
        }

        /* Container frames optimized with premium glassmorphism */
        .topbar, .card, .panel, .table-shell, .form-shell {
            background: var(--panel);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--line);
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: center;
            padding: 16px 28px;
            margin-bottom: 32px;
        }

        /* Beautiful text-clipped gradient title signature */
        .brand { 
            font-size: 24px;
            font-weight: 800; 
            letter-spacing: -0.03em; 
            background: linear-gradient(135deg, #ffffff 0%, #206ac5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nav { 
            display: flex; 
            gap: 12px; 
            align-items: center;
            flex-wrap: wrap; 
        }

        /* Buttons matching the custom brand gradient */
        .btn, .btn-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 0;
            border-radius: 12px;
            padding: 10px 22px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn { 
            background: linear-gradient(135deg, #ffffff 0%, #206ac5 100%); 
            color: #0b0f19; 
            box-shadow: 0 4px 14px rgba(32, 106, 197, 0.2);
        }
        
        .btn:hover { 
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(32, 106, 197, 0.4), 0 0 15px var(--accent-glow);
        }
        
        .btn:active {
            transform: translateY(0);
        }

        .btn-link { 
            background: transparent; 
            color: var(--text); 
            border: 1px solid var(--line); 
        }
        
        .btn-link:hover { 
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(255, 255, 255, 0.15);
        }

        .grid { 
            display: grid; 
            gap: 24px; 
        }

        .table-shell, .form-shell { 
            padding: 32px; 
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
        }

        th, td { 
            padding: 18px 16px; 
            border-bottom: 1px solid var(--line); 
            text-align: left; 
            vertical-align: middle; 
        }

        th { 
            color: var(--muted); 
            font-size: 11px; 
            font-weight: 700;
            text-transform: uppercase; 
            letter-spacing: .1em; 
        }

        tr:last-child td {
            border-bottom: 0;
        }

        .muted { 
            color: var(--muted); 
        }

        /* Refined notifications without standard yellow alerts */
        .flash {
            margin-bottom: 24px;
            padding: 14px 20px;
            border-radius: 14px;
            background: linear-gradient(90deg, rgba(32, 106, 197, 0.15), rgba(32, 106, 197, 0.03));
            border-left: 4px solid var(--accent);
            color: #93c5fd;
            font-size: 14px;
            font-weight: 500;
        }

        .field { 
            display: grid; 
            gap: 8px; 
            margin-bottom: 20px; 
        }

        .field label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--muted);
            font-weight: 600;
        }

        /* Classy input interfaces matching the sapphire glow */
        input, select, textarea {
            width: 100%;
            background: #131a2c;
            color: var(--text);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 12px 16px;
            font: inherit;
            font-size: 15px;
            outline: none;
            transition: all 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(32, 106, 197, 0.15);
            background: #161f33;
        }
        
        input::placeholder {
            color: rgba(255, 255, 255, 0.2);
        }

        textarea { 
            min-height: 140px; 
            resize: vertical; 
        }

        .actions { 
            display: flex; 
            gap: 12px; 
            flex-wrap: wrap; 
        }

        .tiny { 
            font-size: 13px; 
            color: var(--muted); 
            line-height: 1.4;
        }

        @media (max-width: 720px) {
            .wrap { padding: 20px 0 40px; }
            .topbar { flex-direction: column; align-items: stretch; padding: 20px; }
            .brand { text-align: center; }
            .nav { justify-content: center; }
            .table-shell { padding: 16px; }
            table, thead, tbody, th, td, tr { display: block; }
            thead { display: none; }
            tr { padding: 16px 0; border-bottom: 1px solid var(--line); }
            tr:last-child { border-bottom: 0; }
            td { border-bottom: 0; padding: 6px 0; display: flex; justify-content: space-between; }
            td::before { content: attr(data-label) ": "; color: var(--muted); font-weight: 600; margin-right: 8px; }
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

                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
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
        <div class="flash" style="background: rgba(244, 63, 94, 0.1); border-color: rgba(244, 63, 94, 0.2); color: #fecdd3; border-left-color: var(--accent-2);">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="flash" style="background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); color: #a7f3d0; border-left-color: #10b981;">
            {{ session('success') }}
        </div>
    @endif

    @yield('content')
</div>
</body>
</html>