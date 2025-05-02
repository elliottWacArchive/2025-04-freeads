<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes messages reçus - Free Ads</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .unread {
            font-weight: bold;
            background-color: #f0f8ff;
        }
    </style>
</head>
<body>
    @include('components.header')

    <main>
        <h1>Mes messages reçus</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="messages-nav">
            <a href="{{ route('messages.index') }}" class="active">Messages reçus</a>
            <a href="{{ route('messages.sent') }}">Messages envoyés</a>
            <a href="{{ route('messages.create') }}">Nouveau message</a>
        </div>

        @if ($messages->count() > 0)
            <div class="messages-list">
                <table>
                    <thead>
                        <tr>
                            <th>Statut</th>
                            <th>Expéditeur</th>
                            <th>Sujet</th>
                            <th>Annonce concernée</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $message)
                            <tr class="{{ $message->is_read ? '' : 'unread' }}">
                                <td>
                                    @if ($message->is_read)
                                        <span class="read-status">Lu</span>
                                    @else
                                        <span class="unread-status">Non lu</span>
                                    @endif
                                </td>
                                <td>{{ $message->sender->name }}</td>
                                <td>
                                    <a href="{{ route('messages.show', $message->id) }}">
                                        {{ $message->subject }}
                                    </a>
                                </td>
                                <td>
                                    @if ($message->annonce)
                                        <a href="{{ route('annonces.show', $message->annonce->id) }}">
                                            {{ $message->annonce->titre }}
                                        </a>
                                    @else
                                        <span class="text-muted">Aucune</span>
                                    @endif
                                </td>
                                <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('messages.reply', $message->id) }}" class="btn-reply">Répondre</a>
                                    <form method="POST" action="{{ route('messages.destroy', $message->id) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>Vous n'avez aucun message reçu.</p>
        @endif
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Free Ads - Tous droits réservés</p>
    </footer>
</body>
</html>