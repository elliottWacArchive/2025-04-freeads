<link rel="stylesheet" href="{{ asset('css/header.css') }}">
<header class="header">
    <h1><a href="/">Free Ads</a></h1>
    <nav>
        <a href="{{ route('annonces.index') }}">Annonces</a>
        
        @auth
            <a href="{{ route('annonces.create') }}">Publier une annonce</a>
            
            @php
                $unreadCount = Auth::user()->unreadMessages()->count();
            @endphp
            
            <a href="{{ route('messages.index') }}">
                Messages
                @if($unreadCount > 0)
                    <span class="badge">{{ $unreadCount }}</span>
                @endif
            </a>
            
            <a href="{{ route('users.show', Auth::id()) }}">Mon Profil</a>
            <form class="logout-form" method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="logout" type="submit">Déconnexion</button>
            </form>
        @else
            <a href="{{ route('login') }}">Connexion</a>
            <a href="{{ route('register') }}">Inscription</a>
        @endauth
    </nav>
</header>