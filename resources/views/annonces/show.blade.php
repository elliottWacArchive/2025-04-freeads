.php
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $annonce->titre }} - Free Ads</title>
</head>
<body>
    <header>
        <h1>Free Ads - Détail de l'annonce</h1>
        <nav>
            <a href="{{ route('annonces.index') }}">Annonces</a>
            @auth
                <a href="{{ route('annonces.create') }}">Créer une annonce</a>
                <a href="{{ route('users.show', Auth::id()) }}">Mon Profil</a>
            @else
                <a href="{{ route('login') }}">Connexion</a>
                <a href="{{ route('register') }}">Inscription</a>
            @endauth
            <a href="/">Accueil</a>
        </nav>
    </header>

    <main>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <article class="annonce-detail">
            <div class="actions">
                @auth
                    @if ($annonce->user_id === Auth::id())
                        <a href="{{ route('annonces.edit', $annonce->id) }}" class="btn-edit">Modifier cette annonce</a>
                        <form method="POST" action="{{ route('annonces.destroy', $annonce->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">Supprimer cette annonce</button>
                        </form>
                    @else
                        <a href="{{ route('messages.create', ['annonce' => $annonce->id, 'receiver' => $annonce->user_id]) }}" class="btn-contact">Contacter le vendeur</a>
                    @endif
                @endauth
            </div>
            <div class="annonce-images">
                @php
                    $primaryImage = $annonce->images->firstWhere('is_primary', true);
                    $primaryPath = $primaryImage ? $primaryImage->path : ($annonce->images->isNotEmpty() ? $annonce->images->first()->path : $annonce->photo);
                @endphp
                
                {{-- <div class="main-image">
                    @if($primaryPath)
                        <img src="{{ asset('storage/' . $primaryPath) }}" alt="{{ $annonce->titre }}" class="primary-image">
                    @else
                        <img src="{{ asset('images/no-image.jpg') }}" alt="Aucune image disponible">
                    @endif
                </div> --}}
                
                @if($annonce->images->count() > 1)
                    <div class="image-gallery">
                        @foreach($annonce->images as $image)
                            <img src="{{ asset('storage/' . $image->path) }}" 
                                alt="Image de {{ $annonce->titre }}" 
                                class="thumbnail {{ $image->is_primary ? 'active' : '' }}"
                                data-path="{{ $image->path }}">
                        @endforeach
                    </div>
                    
                    <script>
                        document.querySelectorAll('.thumbnail').forEach(thumb => {
                            thumb.addEventListener('click', function() {
                                document.querySelector('.primary-image').src = '{{ asset('storage') }}/' + this.dataset.path;
                                
                                document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
                                this.classList.add('active');
                            });
                        });
                    </script>
                @endif
            </div>

            <div class="annonce-info">
                <h2>{{ $annonce->titre }}</h2>
                <p class="prix">{{ number_format($annonce->prix, 2) }} €</p>
                <p class="date">Publié le {{ $annonce->created_at->format('d/m/Y') }}</p>
                <p class="vendeur">Vendu par: {{ $annonce->user->name }}</p>

                <div class="description">
                    <h3>Description</h3>
                    <p>{{ $annonce->description }}</p>
                </div>

                <div class="actions">
                    @auth
                        @if ($annonce->user_id === Auth::id())
                            <a href="{{ route('annonces.edit', $annonce->id) }}" class="btn-edit">Modifier cette annonce</a>
                            <form method="POST" action="{{ route('annonces.destroy', $annonce->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Supprimer cette annonce</button>
                            </form>
                        @else
                            <button class="btn-contact">Contacter le vendeur</button>
                        @endif
                    @endauth
                </div>
            </div>
        </article>

        <div class="back-link">
            <a href="{{ route('annonces.index') }}">Retour à la liste des annonces</a>
        </div>
    </main>
    
    <footer>
        <p>&copy; {{ date('Y') }} Free Ads - Tous droits réservés</p>
    </footer>
</body>
</html>