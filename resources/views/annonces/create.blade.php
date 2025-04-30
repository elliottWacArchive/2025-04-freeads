<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une annonce - Free Ads</title>
</head>
<body>
    @include('components.header')

    <main>
        <h2>Publier une nouvelle annonce</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('annonces.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="titre">Titre de l'annonce</label>
                <input type="text" id="titre" name="titre" value="{{ old('titre') }}" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="prix">Prix (€)</label>
                <input type="number" id="prix" name="prix" step="0.01" min="0" value="{{ old('prix') }}" required>
            </div>
            
            <div class="form-group">
                <label for="images">Photos du produit</label>
                <input type="file" id="images" name="images[]" accept="image/*" multiple required>
                <small>Formats acceptés : JPEG, PNG, JPG, GIF, WEBP. Vous pouvez sélectionner plusieurs images.</small>
                <p>La première image sélectionnée sera utilisée comme image principale.</p>
            </div>
            
            <div class="form-group">
                <button type="submit">Publier l'annonce</button>
                <a href="{{ route('annonces.index') }}">Annuler</a>
            </div>
        </form>
    </main>
    
    <footer>
        <p>&copy; {{ date('Y') }} Free Ads - Tous droits réservés</p>
    </footer>
</body>
</html>