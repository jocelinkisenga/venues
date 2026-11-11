<?php

namespace App\Actions;

use App\Models\Token;

/**
 *  
 */
class IncrementTokens 
{
	
	public function handler(int $entryTokens){
		Token::update([
			"total_tokens" => $entryTokens
		]);
	}
}