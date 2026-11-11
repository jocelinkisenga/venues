<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
protected $fillable = [
        'user_id', 
        'chat_id', // Essentiel ici
        'role', 
        'message', 
        'file_path', 
        'file_name', 
        'file_type'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
