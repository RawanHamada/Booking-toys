<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\MailResetPasswordToken;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'confirm_password',
        'avatar',
        'date_of_birth',
        'gender',
        'accept',
        'status',
        // 'player_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'confirm_password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'confirm_password' => 'hashed',

    ];

    // Relation With Player
    public function player(){
        return $this->belongsTo(Player::class, 'player_id')->withDefault();
    }


    public function sendPasswordResetNotification($token)
    {
        $this->code = Str::random(4);
        $this->save();

        $this->notify(new MailResetPasswordToken($token, $this->code));
        // $url = 'https://spa.test/reset-password?token=' . $token;

        // $this->notify(new ResetPasswordNotification($url));
    }
    // Accessor Methods
    public function getImageAttribute(){
        if(!$this->avatar) {
            return asset('assets/avatar.png');
        }
        return asset('uploads/avatar/' . $this->avatar);

    }
}
