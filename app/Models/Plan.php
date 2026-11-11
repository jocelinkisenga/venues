<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ["name","price","slug","analysis_quota","description"];

    
public function scopeWhereNameLike($query, $search)
{
    
    return $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
}
}
