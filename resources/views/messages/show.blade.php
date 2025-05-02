<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message: {{ $message->subject }} - Free Ads</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    @include('components.header')

    <main>
        <h1>Message</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="messages-nav">
            <a href="{{ route('messages.index') }}">Messages reçus</a>
            <a href="{{ route('messages.sent') }}">Messages envoyés</a>
            <a href="{{ route('messages.create') }}">Nouveau message</a>
        </div>

        <div class="message-details">
            <div class="message-header">
                <p><strong>Sujet:</strong> {{ $message->subject }}</p>
                <p>
                    <strong>{{ Auth::id() == $message->sender_id ? 'À:' : 'De:' }}</strong> 
                    {{ Auth::id() == $message->sender_id ? $message->receiver->name : $message->sender->name }}
                </p>
                <p><strong>Date:</strong> {{ $message->created_at->format('d/m/Y H:i') }}</p>
                
                @if ($message->annonce)
                    <p>
                        <strong>Annonce concernée:</strong> 
                        <a href="{{ route('annonces.show', $message->annonce->id) }}">{{ $message->annonce->titre }}</a>
                    </p>
                @endif
            </div>
            
            <div class="message-content">
                {{ $message->content }}
            </div>
            
            <div class="message-actions">
                @if (Auth::id() == $message->receiver_id)
                    <a href="{{ route('messages.reply', $message->id) }}" class="btn-reply">Répondre</a>
                @endif
                
                <form method="POST" action="{{ route('messages.destroy', $message->id) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete">Supprimer</button>
                </form>
                
                <a href="{{ Auth::id() == $message->sender_id ? route('messages.sent') : route('messages.index') }}" class="btn-back">Retour</a>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Free Ads - Tous droits réservés</p>
    </footer>
</body>
</html>