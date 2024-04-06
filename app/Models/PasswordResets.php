<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResets extends Model
{
    use HasFactory;

     protected $table ='password_resets';

    protected $fillable =['password','email','token'];

    public $timestamps = false;
    protected $dates = ['created_at'];

}
