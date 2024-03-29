<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class Manager extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    public $timestamps = true;
    protected $primaryKey = "manager_id";

    protected $fillable = [
        'manager_fname',
        'manager_lname',
        'manager_mname',
        'manager_phone',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function orders(){
        return $this->hasMany(Order::class, 'f_manager_id');
    }
}
