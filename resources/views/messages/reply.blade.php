<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Répondre au message - Free Ads</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    @include('components.header')

    <main>
        <h1>Répondre au message</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="original-message">
            <h2>Message original</h2>
            <p><strong>De:</strong> {{ $message->sender->name }}</p>
            <p><strong>Sujet:</strong> {{ $message->subject }}</p>
            <p><strong>Date:</strong> {{ $message->created_at->format('d/m/Y H:i') }}</p>
            <div class="message-content">
                <p>{{ $message->content }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('messages.store') }}">
            @csrf
            
            <input type="hidden" name="receiver_id" value="{{ $receiver->id }}">
            
            @if ($annonce)
                <input type="hidden" name="annonce_id" value="{{ $annonce->id }}">
                <div class="form-group">
                    <label>Annonce concernée</label>
                    <p><a href="{{ route('annonces.show', $annonce->id) }}">{{ $annonce->titre }}</a></p>
                </div>
            @endif
            
            <div class="form-group">
                <label for="subject">Sujet</label>
                <input type="text" name="subject" id="subject" required value="{{ $subject }}">
            </div>
            
            <div class="form-group">
                <label for="content">Message</label>
                <textarea name="content" id="content" rows="10" required>{{ old('content') }}</textarea>
            </div>
            
            <div class="form-group">
                <button type="submit">Envoyer</button>
                <a href="{{ route('messages.index') }}">Annuler</a>
            </div>
        </form>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Free Ads - Tous droits réservés</p>
    </footer>
</body>
</html>