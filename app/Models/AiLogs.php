<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiLogs extends Model
{
  protected $fillable = ["user_id", "chat_id", "tokens_used"];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
