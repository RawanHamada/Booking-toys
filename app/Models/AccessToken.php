<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    use HasFactory;

    protected $fillable = ['tokenable','name','token','abilities','last_used_at','expires_at'];

    protected $table = 'personal_access_tokens';

}
