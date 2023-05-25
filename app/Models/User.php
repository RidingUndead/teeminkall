<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use  HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'password',
        'email',
        'groupid',
        'status',
        'rank',
        'kicked_until',
    ];

    public function groups()
    {
        return this->belongsTo(Group::class);
    }
}
