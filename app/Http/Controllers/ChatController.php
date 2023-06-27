<?php

namespace App\Http\Controllers;

use App\Events\ChatMessage;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\Chat\ChatResource;
use App\Http\Resources\UserResource;
use App\Models\ChatSession;

class ChatController extends Controller
{

    public function getMessages(User $user)
    { 
        $messages = Chat::between(auth()->id(),$user->id)->get();
        
        return response()->json([   
            'messagesArr' => ChatResource::collection($messages),
            'recipientObj' => new UserResource($user),
        ]);
    }

    public function getSession(User $user)
    {
        $session = Chat::between(auth()->id(),$user->id)->first()?->session ?? ChatSession::create();
        

        return response()->json([
            'session' => $session 
        ]);
    }

    public function getChats()
    {
        //get all chats that the user is involved in and group them by the recipient
        $chats = auth()->user()->chats()->with('recipient')
        ->whereIn('id', function ($query) {
        $query->selectRaw('MAX(id)')
            ->from('chats')
            ->groupBy('recipient_id','user_id');
        })
        ->latest()->get();


        return response()->json([
            'chats' => ChatResource::collection($chats)
        ]);
    }

    public function sendMessage(Request $request,User $user)
    {
        try{
            $request->validate([
               'message' => 'required|max:2000',
               'session' => 'required|string|size:36',
           ]);

          //get the session between the authenticated user and the recipient first and if it doesn't exist create it
            $firstChat = Chat::between(auth()->id(),$user->id)->first();
            $session = $firstChat ? $firstChat->session : ChatSession::where('uuid',$request->session)->first();

           $chat = $request->user()->chats()->create([
               'recipient_id' => $user->id,
               'message' => $request->message,
               'session_id' => $session->id
           ]);

   
            broadcast(new ChatMessage($chat))->toOthers();
   
           return response()->json([
               'mssg' => new ChatResource($chat)
           ]);

        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }
}
