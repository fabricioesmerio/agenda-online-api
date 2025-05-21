<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class UserPassword extends Model
{
    use Uuid;

    protected $fillable = ['user_id', 'password'];
}
