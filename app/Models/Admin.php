<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable{
    
    use HasFactory;
    public $timestamps = true;

    protected $primaryKey = "admin_id";

    protected $fillable = [
        'admin_fname',
        'admin_lname',
        'admin_phone',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

}
