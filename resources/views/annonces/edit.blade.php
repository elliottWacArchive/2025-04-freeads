<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'annonce - Free Ads</title>
</head>
<body>
    @include('components.header')

    <main>
        <h2>Modifier votre annonce</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('annonces.update', $annonce->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="titre">Titre de l'annonce</label>
                <input type="text" id="titre" name="titre" value="{{ old('titre', $annonce->titre) }}" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="5" required>{{ old('description', $annonce->description) }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="prix">Prix (€)</label>
                <input type="number" id="prix" name="prix" step="0.01" min="0" value="{{ old('prix', $annonce->prix) }}" required>
            </div>
            
            @if($annonce->images->isNotEmpty())
                <div class="form-group">
                    <label>Images actuelles</label>
                    <div class="current-images">
                        @foreach($annonce->images as $image)
                            <div class="image-container">
                                <img src="{{ asset('storage/' . $image->path) }}" alt="Image de l'annonce">
                                <div class="image-actions">
                                    <label>
                                        <input type="radio" name="primary_image" value="{{ $image->id }}" {{ $image->is_primary ? 'checked' : '' }}>
                                        Image principale
                                    </label>
                                    <label>
                                        <input type="checkbox" name="delete_images[]" value="{{ $image->id }}">
                                        Supprimer
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <div class="form-group">
                <label for="new_images">Ajouter de nouvelles photos</label>
                <input type="file" id="new_images" name="new_images[]" accept="image/*" multiple>
                <small>Formats acceptés : JPEG, PNG, JPG, GIF, WEBP. Vous pouvez sélectionner plusieurs images.</small>
            </div>
            
            <div class="form-group">
                <button type="submit">Mettre à jour l'annonce</button>
                <a href="{{ route('annonces.show', $annonce->id) }}">Annuler</a>
            </div>
        </form>
    </main>
    
    <footer>
        <p>&copy; {{ date('Y') }} Free Ads - Tous droits réservés</p>
    </footer>
</body>
</html>