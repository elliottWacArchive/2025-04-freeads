<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toutes les annonces - Free Ads</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    @include('components.header')


    <main>
        <h2>Annonces disponibles</h2>

        <div class="search-form">
            <form method="GET" action="{{ route('annonces.index') }}">
                <div class="filter-group">
                    <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}">
                    <button type="submit">Rechercher</button>
                </div>

                <details {{ request()->hasAny(['categorie', 'prix_min', 'prix_max', 'date_min', 'order']) ? 'open' : '' }}>
                    <summary>Filtres avancés</summary>
                    
                    <div class="filter-group">
                        <label for="categorie">Catégorie</label>
                        <select name="categorie" id="categorie">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie }}" {{ request('categorie') == $categorie ? 'selected' : '' }}>
                                    {{ ucfirst($categorie) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Prix</label>
                        <div class="price-inputs">
                            <input type="number" name="prix_min" placeholder="Min" min="0" value="{{ request('prix_min') }}">
                            <input type="number" name="prix_max" placeholder="Max" min="0" value="{{ request('prix_max') }}">
                        </div>
                    </div>

                    <div class="filter-group">
                        <label for="date_min">Date de publication (à partir de)</label>
                        <input type="date" name="date_min" id="date_min" value="{{ request('date_min') }}">
                    </div>

                    <div class="filter-group">
                        <label for="order">Trier par</label>
                        <select name="order" id="order">
                            <option value="latest" {{ request('order') == 'latest' || !request('order') ? 'selected' : '' }}>
                                Plus récentes
                            </option>
                            <option value="oldest" {{ request('order') == 'oldest' ? 'selected' : '' }}>
                                Plus anciennes
                            </option>
                            <option value="price_asc" {{ request('order') == 'price_asc' ? 'selected' : '' }}>
                                Prix croissant
                            </option>
                            <option value="price_desc" {{ request('order') == 'price_desc' ? 'selected' : '' }}>
                                Prix décroissant
                            </option>
                        </select>
                    </div>
                </details>

                <div class="filter-group">
                    <button type="submit">Appliquer les filtres</button>
                    <a href="{{ route('annonces.index') }}">Réinitialiser</a>
                </div>
            </form>
        </div>


        @if (session('success'))
            <div>
                {{ session('success') }}
            </div>
        @endif

        @if ($annonces->count() > 0)
            <div class="annonces-list">
                @foreach ($annonces as $annonce)
                    <div class="annonce-card">
                        @php
                        $imagePath = '';
                        if ($annonce->images->isNotEmpty()) {
                            $primaryImage = $annonce->images->firstWhere('is_primary', true);
                            $imagePath = $primaryImage ? $primaryImage->path : $annonce->images->first()->path;
                        } elseif ($annonce->photo) {
                            $imagePath = $annonce->photo;
                        }
                    @endphp
                    <img src="{{ $imagePath ? asset('storage/' . $imagePath) : asset('images/no-image.jpg') }}" alt="{{ $annonce->titre }}">
                    <h3>{{ $annonce->titre }}</h3>
                        <p class="prix">{{ number_format($annonce->prix, 2) }} €</p>
                        <p class="date">Publié le {{ $annonce->created_at->format('d/m/Y') }}</p>
                        <div class="actions">
                            <a href="{{ route('annonces.show', $annonce->id) }}">Voir détails</a>
                            @auth
                                @if ($annonce->user_id === Auth::id())
                                    <a href="{{ route('annonces.edit', $annonce->id) }}">Modifier</a>
                                    <form method="POST" action="{{ route('annonces.destroy', $annonce->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Supprimer</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="pagination">
                {{ $annonces->links() }}
            </div>
        @else
            <p>Aucune annonce disponible pour le moment.</p>
        @endif
        
        @auth
            <div class="create-annonce">
                <a href="{{ route('annonces.create') }}">Publier une nouvelle annonce</a>
            </div>
        @endauth
    </main>
    
    <footer>
        <p>&copy; {{ date('Y') }} Free Ads - Tous droits réservés</p>
    </footer>
</body>
</html>