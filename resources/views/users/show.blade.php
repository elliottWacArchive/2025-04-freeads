<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - {{ $user->name }}</title>
</head>
<body>
    <h1>Mon profil</h1>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="profile-info">
        <p><strong>Nom :</strong> {{ $user->name }}</p>
        <p><strong>Email :</strong> {{ $user->email }}</p>
        <p><strong>Statut de l'email :</strong> 
            @if ($user->email_verified_at)
                <span class="verified">Vérifié</span>
            @else
                <span class="not-verified">Non vérifié</span>
                <a href="{{ route('verification.notice') }}">Vérifier maintenant</a>
            @endif
        </p>
        <p><strong>Compte créé le :</strong> {{ $user->created_at->format('d/m/Y') }}</p>
    </div>
    
    <div class="actions">
        <a href="{{ route('users.edit', $user->id) }}">Modifier mon profil</a>
        
        <form method="POST" action="{{ route('users.destroy', $user->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte? Cette action est irréversible.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="delete-btn">Supprimer mon compte</button>
        </form>
    </div>
    
    <div>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="/">Retour à l'accueil</a>
    </div>
</body>
</html>