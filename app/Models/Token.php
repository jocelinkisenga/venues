<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
               protected $fillable = [ "entry_tokens","output_tokens",
                           "supplier",
                           "price",
                         "total_tokens"];

      // protected static function booted () {
      //    static::creating(function($token) {
      //       $lastTokens = static::where('supplier', $token->supplier)->latest("id")->first();

      //       $totalTokens = $lastTokens ? $lastTokens->total_tokens : 0;

      //       $token->total_tokens = $totalTokens + $token->entry_tokens;
      //    });
      // } 
}
