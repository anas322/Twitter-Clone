<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Events\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Http\Request;
use App\Notifications\NewMessage;
use App\Http\Resources\UserResource;
use App\Http\Resources\Chat\ChatResource;
use App\Http\Resources\Chat\ChatSessionResource;

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
        $session = ChatSession::between(auth()->id(),$user->id)->first() ?? ChatSession::create([
            'first_user' => auth()->id(),
            'second_user' => $user->id,]);
        

        return response()->json([
            'session' => $session 
        ]);
    }

    public function getChats()
    {
        //get all chats that the user is involved in and group them by the recipient
        // $chats = auth()->user()->chats()->with('recipient')
        // ->whereIn('id', function ($query) {
        // $query->selectRaw('MAX(id)')
        //     ->from('chats')
        //     ->groupBy('recipient_id','user_id');
        // })
        // ->latest()->get();
        
        $chatSessions = ChatSession::where('first_user',auth()->id())
                ->orWhere('second_user',auth()->id())
                ->get();

        if($chatSessions->isEmpty()){
            return response()->json([
                'chats' => []
            ]);
        }

        $chats = ChatSessionResource::collection($chatSessions);
        
        if(json_encode($chats[0]) == '[]'){
            return response()->json([
                'chats' => []
            ]);
        }

        return response()->json([
            // 'chats' => ChatResource::collection($chats)
            'chats' => $chats
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
            $firstChat = ChatSession::between(auth()->id(),$user->id)->first();
            $session = $firstChat ?? ChatSession::create([
               'first_user' => auth()->id(),
               'second_user' => $user->id,
              ]);

           $chat = $request->user()->chats()->create([
               'recipient_id' => $user->id,
               'message' => $request->message,
               'session_id' => $session->id
           ]);

   
            broadcast(new ChatMessage($chat))->toOthers();
            
            $user->notify(
                new NewMessage($session)
            );
   
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
