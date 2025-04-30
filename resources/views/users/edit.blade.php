<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon profil - Free Ads</title>
</head>
<body>
    <h1>Modifier mon profil</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PUT')
        
        <div>
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>
        
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            @if ($user->email != old('email', $user->email))
                <p class="warning">Attention : Changer votre email nécessitera une nouvelle vérification.</p>
            @endif
        </div>
        
        <h2>Changer de mot de passe</h2>
        <p>Laissez les champs vides si vous ne souhaitez pas changer de mot de passe</p>
        
        <div>
            <label for="current_password">Mot de passe actuel</label>
            <input type="password" id="current_password" name="current_password">
        </div>
        
        <div>
            <label for="password">Nouveau mot de passe</label>
            <input type="password" id="password" name="password">
        </div>
        
        <div>
            <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>
        
        <div>
            <button type="submit">Mettre à jour mon profil</button>
        </div>
    </form>
    
    <div>
        <a href="{{ route('users.show', $user->id) }}">Annuler</a>
        <a href="/">Retour à l'accueil</a>
    </div>
</body>
</html>