<?php

namespace App\Modules\Users\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Users extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $fillable = [
        'username',
        'FirstName', // Corrected from 'FisrtName'
        'LastName',
        'email',
        'password',

    ];
}
