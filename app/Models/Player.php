<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_name',
        'balance',
        'game_id'
    ];

    // Relation With game
    public function game(){
        return $this->hasMany(Game::class, 'game_id')->withDefault();
    }

    // Relation With user
    public function user(){
        return $this->belongsTo(User::class, 'player_id')->withDefault();
    }
}
