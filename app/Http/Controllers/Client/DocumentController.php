<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Chat;
class DocumentController extends Controller
{
    public function index () {
      $documents = auth()->user()->chatMessages()
            ->whereNotNull('file_path') // Uniquement les messages avec un fichier
            ->where('role', 'user')      // Uniquement ceux envoyés par l'utilisateur
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($message) {
                return [
                    'id'             => $message->id,
                    'name'           => $message->file_name,
                    'type'           => $message->file_type,
                    'size'           => $this->formatFileSize($message->file_path),
                    'date'           => $message->created_at->format('d/m/Y H:i'),
                    'context_prompt' => $message->message,
                ];
            });
        return view("client.documents.documents", compact('documents'));
    }
    
        public function hystory()
    {
        
    $chats = Chat::where('user_id', auth()->id())->latest()->get();



        return view('client.chats.chatHistory', [
            'chats' => $chats
        ]);
    }

           private function formatFileSize(?string $filePath): string
    {
        if (!$filePath || !\Storage::disk('local')->exists($filePath)) {
            return 'N/A';
        }

        $bytes = \Storage::disk('local')->size($filePath);
        
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        
        return number_format($bytes / 1024, 2) . ' KB';
    }
}
