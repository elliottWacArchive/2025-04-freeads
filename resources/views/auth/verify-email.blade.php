<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification d'email - Free Ads</title>
</head>
<body>
    <h1>Vérifiez votre adresse email</h1>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session('resent'))
        <div class="alert alert-success">
            Un nouveau lien de vérification a été envoyé à votre adresse email.
        </div>
    @endif
    
    <p>Avant de continuer, veuillez vérifier votre email pour un lien de vérification.</p>
    <p>Si vous n'avez pas reçu l'email, vous pouvez en demander un autre.</p>
    
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">Renvoyer l'email de vérification</button>
    </form>
</body>
</html>