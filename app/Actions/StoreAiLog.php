<?php
namespace App\Actions;
use App\Models\AiLogs;
use App\Models\Token;
class StoreAiLog
{
  public function handler(?int $chatId, $usage)
  {
    $usadeTokens =
      ($usage->promptTokens ?? 0) + ($usage->completionTokens ?? 0);

    AiLogs::create([
      "user_id" => auth()->user()->id,
      "chat_id" => $chatId,
      "tokens_used" => $usadeTokens,
    ]);

    $lastTokens = Token::latest("id")->value("total_tokens") ?? 0;

    Token::create([
      "output_tokens" => $usadeTokens,
      "total_tokens" => $lastTokens - $usadeTokens,
    ]);

    
  }
}
