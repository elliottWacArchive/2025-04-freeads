<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes messages envoyés - Free Ads</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    @include('components.header')

    <main>
        <h1>Mes messages envoyés</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="messages-nav">
            <a href="{{ route('messages.index') }}">Messages reçus</a>
            <a href="{{ route('messages.sent') }}" class="active">Messages envoyés</a>
            <a href="{{ route('messages.create') }}">Nouveau message</a>
        </div>

        @if ($messages->count() > 0)
            <div class="messages-list">
                <table>
                    <thead>
                        <tr>
                            <th>Destinataire</th>
                            <th>Sujet</th>
                            <th>Annonce concernée</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $message)
                            <tr>
                                <td>{{ $message->receiver->name }}</td>
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
            <p>Vous n'avez aucun message envoyé.</p>
        @endif
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Free Ads - Tous droits réservés</p>
    </footer>
</body>
</html>