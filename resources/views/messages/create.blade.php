<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message - Free Ads</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    @include('components.header')

    <main>
        <h1>Nouveau message</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="messages-nav">
            <a href="{{ route('messages.index') }}">Messages reçus</a>
            <a href="{{ route('messages.sent') }}">Messages envoyés</a>
            <a href="{{ route('messages.create') }}" class="active">Nouveau message</a>
        </div>

        <form method="POST" action="{{ route('messages.store') }}">
            @csrf
            
            <div class="form-group">
                <label for="receiver_id">Destinataire</label>
                @if (isset($receiver))
                    <input type="hidden" name="receiver_id" value="{{ $receiver->id }}">
                    <p>{{ $receiver->name }}</p>
                @else
                    <select name="receiver_id" id="receiver_id" required>
                        <option value="">Sélectionnez un destinataire</option>
                        @foreach (App\Models\User::where('id', '!=', Auth::id())->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            
            @if (isset($annonce))
                <input type="hidden" name="annonce_id" value="{{ $annonce->id }}">
                <div class="form-group">
                    <label>Annonce concernée</label>
                    <p><a href="{{ route('annonces.show', $annonce->id) }}">{{ $annonce->titre }}</a></p>
                </div>
            @endif
            
            <div class="form-group">
                <label for="subject">Sujet</label>
                <input type="text" name="subject" id="subject" required value="{{ isset($annonce) ? 'À propos de: ' . $annonce->titre : old('subject') }}">
            </div>
            
            <div class="form-group">
                <label for="content">Message</label>
                <textarea name="content" id="content" rows="10" required>{{ old('content') }}</textarea>
            </div>
            
            <div class="form-group">
                <button type="submit">Envoyer</button>
                <a href="{{ url()->previous() }}">Annuler</a>
            </div>
        </form>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Free Ads - Tous droits réservés</p>
    </footer>
</body>
</html>