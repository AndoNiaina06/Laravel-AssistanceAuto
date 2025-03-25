<?php

namespace App\Http\Controllers;

use App\Events\PostCreateEvent;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessageSent;
use Tymon\JWTAuth\Facades\JWTAuth;

class ChatController extends Controller
{

    public function index(){
        $event = new PostCreateEvent(['name' => 'post']);
        event($event);
        dd();
    }
    // Méthode pour envoyer un message
    public function sendMessage(Request $request)
    {
    $user = JWTAuth::parseToken()->authenticate();

    if (!$user) {
    return response()->json(['error' => 'Utilisateur non authentifié'], 401);
    }

    // Validation des données
    $request->validate([
    'message' => 'required|string',
    'recipient_id' => 'required|exists:users,id',
    ]);

    // Créer un message dans la base de données
//    $message = Message::create([
//    'user_id' => $user->id,
//    'message' => $request->message,
//    'recipient_id' => $request->recipient_id,
//    ]);
    $message = $request->message;
    // Diffusion du message à l'utilisateur destinataire
    broadcast(new MessageSent($user, $message, $request->recipient_id))->toOthers();

    return response()->json($message);
    }

    public function getMessages(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        // Récupérer les messages échangés entre l'utilisateur authentifié et l'utilisateur sélectionné
        $recipientId = $request->query('recipient_id');
        if (!$recipientId) {
            return response()->json(['error' => 'Recipient ID is required'], 400);
        }

        // Récupérer les messages avec les informations de l'utilisateur
        $messages = Message::with('user') // Charger la relation 'user'
        ->where(function($query) use ($user, $recipientId) {
            $query->where('user_id', $user->id)
                ->where('recipient_id', $recipientId);
        })
            ->orWhere(function($query) use ($user, $recipientId) {
                $query->where('user_id', $recipientId)
                    ->where('recipient_id', $user->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }


    // Méthode pour afficher un message spécifique
    public function show($id)
    {
        $message = Message::find($id);
        if (!$message) {
        return response()->json(['error' => 'Message non trouvé'], 404);
        }
        return response()->json($message);
    }
}
