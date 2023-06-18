<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'cafe_id',
        'user_id',
        // 'game_id',
        'num__of_seats',
        'date',
        'time',
        'price',
    ];

    // Relation With game
    // public function game(){
    //     return $this->belongsTo(Game::class, 'game_id')->withDefault();
    // }

    // Relation With user
    public function user(){
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
    // Relation With cafe
    public function cafe(){
        return $this->belongsTo(Cafe::class, 'cafe_id')->withDefault();
    }
}
