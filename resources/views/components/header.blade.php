<head>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
</head>

<body>
    <header class="header">
        <h1>Free Ads</h1>
        <nav>
            <a href="{{ route('annonces.index') }}">Annonces</a>
            <a href="/">Accueil</a>
        </nav>
        @auth
            <div class="user-info">
                <span>Bienvenue, {{ Auth::user()->name }}</span>
                <a href="{{ route('users.show', Auth::id()) }}">Mon Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout" type="submit">Déconnexion</button>
                </form>
            </div>
        @else
            <div class="auth-links">
                <a href="{{ route('login') }}">Connexion</a>
                <a href="{{ route('register') }}">Inscription</a>
            </div>
        @endauth
    </header>
</body>